<?php

namespace App\Http\Controllers\Api\Web\Termin;

use Exception;
use OpenSearch\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ContactDataRecord;
use App\Http\Controllers\Controller;
use App\Traits\ContactDataRecords\OrderByDirection;
use App\Http\Resources\Termin\TerminOverviewResource;
use App\Models\BrokerUser;

class GetTerminDataController extends Controller
{
    use OrderByDirection;


    private $indexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        if(!env('OPENSEARCH_ENABLED')) {
            return [
                'data'  =>  [],
                'meta'  =>  []
            ];
        }
        $per_page = request('per_page', 25);
        $current_page = request('page', 1);

        $queryData = $this->getRecords($client, $current_page, $per_page);
        $records = $queryData['records'];
        $total = $queryData['total'];

        // return $records;
        $contactDataRecords = ContactDataRecord::query()
            ->with(['allocation'=>function($q){
                $q->select(['id','contact_data_record_id', 'user_id','broker_user_id','internal_user_id', 'organization_element_id', 'broker_id', 'type']);
                $q->with(['user:id,full_name', 'broker:id,name', 'organizationElement:id,name']);
            }])
            ->select(['id','correspondence_language', 'first_name', 'last_name', 'contact_record_status'])
            ->WithLastAppointment()
            ->whereIn('id', $records)
            ->when(count($records), fn($q)=>$q->orderByRaw("field(id," . implode(',', $records) . ")"))
            ->get()
            ;

        // return $contactDataRecords;
        return response([
            'data'  =>  TerminOverviewResource::collection($contactDataRecords),
            'meta'  =>  customPagination($total, $per_page)
        ]);
    }


    public function getRecords(Client $client,  $current_page, $per_page, array $additional_queries = []){
       $search =  $client->search([
           'index' => $this->indexName,
           'body' => $this->buildQuery($per_page, $current_page, $additional_queries)
       ]);

       $total = 0;
       $records = [];
       if(isset($search['hits']) && isset($search['hits']['total'])){
           $total = $search['hits']['total']['value'] ?? $search['hits']['total'];
       }
       if(isset($search['hits']) && isset($search['hits']['hits'])){
           $records = array_column($search['hits']['hits'], '_id');
       }

       return [
           'total'     =>  $total,
           'records'   =>  $records
       ];
   }



    private function buildQuery($per_page, $current_page, $additional_queries){
        $customer_company_id = auth()->user()->customer_company_id;

        $from = (($current_page - 1) * $per_page);

        $broker_user = BrokerUser::where('user_id', auth()->user()->id)->first();

        // info($broker_user);

        $broker_users = BrokerUser::where('broker_id', $broker_user->broker_id)->pluck('id')->toArray();

        // info($broker_users);

        if($broker_user->role == BrokerUser::ADMIN){
            $filters = [
                [
                    'term'=>[
                        'customer_company_id'=> $customer_company_id
                    ]
                ],
                [
                    'terms'=>[
                        'category.keyword'=>  [
                            'termination_appointment',
                        ]
                    ]
                ],
                [
                    "bool"  => [
                        "should" => [
                            [
                                'term'=>[
                                    "allocation.broker_id" => $broker_user->broker_id
                                ]
                            ],
                            [
                                'term'=>[
                                    "allocation.broker_user_id" => $broker_user->id
                                ]
                            ],
                            [
                                'terms'=>[
                                    "allocation.broker_user_id" => $broker_users
                                ]
                            ],
                        ]
                    ]
                ]
            ];
        }else if($broker_user->role == BrokerUser::INTERMEDIARY){
            $filters = [
                [
                    'term'=>[
                        'customer_company_id'=> $customer_company_id
                    ]
                ],
                [
                    'terms'=>[
                        'category.keyword'=>  [
                            'termination_appointment',
                        ]
                    ]
                ],
                [
                    "bool"  => [
                        "should" => [
                            [
                                'term'=>[
                                    "allocation.broker_id" => $broker_user->broker_id
                                ]
                            ],
                        ]
                    ]
                ]
            ];
        }




        if(request()->has('search') && !is_null(request('search'))){
            $filters[] = [
                "bool"=> [
                    "should" => [
                        [
                            'multi_match'   =>   [
                                'query' =>  request('search', ""),
                                "type"=> "phrase_prefix",
                                'fields' => ['first_name', 'last_name', 'full_name', 'email', 'street', 'zip_code', 'canton', 'phone_number', 'full_phone_number',]
                            ]
                        ],
                        [
                            "nested" => [
                                "path"=> "last_appointment",
                                "query"=> [
                                    'multi_match'   =>   [
                                        'query' =>  request('search', ""),
                                        "type"=> "phrase_prefix",
                                        'fields' => ['last_appointment.prefix_id']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        if(request('termin_start_date') && request('termin_end_date')){
            try {
                $termin_start_date = Carbon::parse(request('termin_start_date'))->toDateString();
                $termin_end_date = Carbon::parse(request('termin_end_date'))->toDateString();
                $filters[]  = [
                    "bool"=> [
                        "should" => [
                            [
                                "nested" => [
                                    "path"=> "last_appointment",
                                    "query"=> [
                                        "range" => [
                                            "last_appointment.appointment_date" => ["gte" => $termin_start_date, "lte" => $termin_end_date]
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            } catch (Exception $e) {
            }
        }

        if(request('termin_start_time') && request('termin_end_time')){
            try {
                $termin_start_time =  Carbon::createFromFormat('H:i', request('termin_start_time'))->format('H:i');
                $termin_end_time = Carbon::createFromFormat('H:i', request('termin_end_time'))->format('H:i');

                $filters[]  = [
                    "bool"=> [
                        "should" => [
                            [
                                "nested" => [
                                    "path"=> "last_appointment",
                                    "query"=> [
                                        "range" => [
                                            "last_appointment.appointment_time" => ["gte" => $termin_start_time, "lte" => $termin_end_time, "format"=> "hour_minute"]
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            } catch (Exception $e) {
            }
        }

        if(request('allocation_start_date') && request('allocation_end_date')){
            try {
                $allocation_start_date = Carbon::parse(request('allocation_start_date'))->toDateString();
                $allocation_end_date = Carbon::parse(request('allocation_end_date'))->toDateString();

                $filters[]  = [
                    "range" => [
                        "allocation.created_at" => ["gte" => $allocation_start_date, "lte" => $allocation_end_date]
                    ],
                ];
            } catch (Exception $e) {
            }
        }
        // if(request('internal_users')){
        //     $internal_user_ids= explode(',', request('internal_users'));
        //     if(count($internal_user_ids)){
        //          $filters[]  = [
        //             "terms" => [
        //                 "allocation.user_id" => $internal_user_ids
        //             ],
        //         ];
        //     }
        // }

        if(request('intermediaries')){
            $intermediaries= explode(',', request('intermediaries'));
            if(count($intermediaries)){
                 $filters[]  = [
                    "terms" => [
                        "allocation.user_id" => $intermediaries
                    ],
                ];
            }
        }
        if(request('cantons')){
            $cantons= explode(',', request('cantons'));
            if(count($cantons)){
                 $filters[]  = [
                    "terms" => [
                        "canton.keyword" => $cantons
                    ],
                ];
            }
        }

        if(request('correspondence_languages')){
            $correspondence_languages= explode(',', request('correspondence_languages'));
            if(count($correspondence_languages)){
                 $filters[]  = [
                    "terms" => [
                        "correspondence_language.keyword" => $correspondence_languages
                    ],
                ];
            }
        }
        if(request('other_languages')){
            $other_languages= explode(',', request('other_languages'));
            if(count($other_languages)){
                 $filters[]  = [
                    "terms" => [
                        "other_languages.keyword" => $other_languages
                    ],
                ];
            }
        }

        if(request('contact_record_status')){
            $contact_record_status= explode(',', request('contact_record_status'));
            if(count($contact_record_status)){
                 $filters[]  = [
                    "terms" => [
                        "contact_record_status.keyword" => $contact_record_status
                    ],
                ];
            }
        }

        if(count($additional_queries)){
            foreach($additional_queries as $query) {
                $filters[] = $query;
            }
        }

        return [
            'size' => $per_page,
            'from' => $from,
            'query' => [
                'bool'  => [
                    'must'=> $filters
                ]
            ],
            'sort'=> $this->generateSortQuery(),
            // '_source'=>  ['user_id', 'prefix_id','last_feedback', 'first_name', 'last_name', 'canton',],
            // '_source'=>  ['contact_record_status'],
            '_source'=>  false,
            'track_total_hits'=> true
        ];
    }



    protected function generateSortQuery(){

        // $order_by = 'created_at';
        $order_direction = strtolower(request('direction')) == 'asc' ? 'asc': 'desc';

        if(request()->has('order_by') && !is_null(request('order_by'))) {
            if(in_array(request('order_by'), ['first_name', 'last_name', ])){
                $order_by = request('order_by').'.keyword';

                return [
                    $order_by  =>  [
                        'order' =>   $order_direction,
                        "missing"=> "_last"
                    ]
                ];
            }
            if(request('order_by') == 'appointment_id'){
                return [
                    'last_appointment.prefix_id.keyword'=> [
                        'order'             =>  $order_direction,
                        'unmapped_type'     =>  'long',
                        'nested_path'       =>  'last_appointment'
                    ]
                ];
            }

            if(request('order_by') == 'appointment_date'){
                return [
                    'last_appointment.appointment_date'=> [
                        'order'             =>  $order_direction,
                        'unmapped_type'     =>  'long',
                        'nested_path'       =>  'last_appointment'
                    ]
                ];
            }
            if(request('order_by') == 'appointment_time'){
                return [
                    'last_appointment.appointment_time'=> [
                        'order'             =>  $order_direction,
                        'unmapped_type'     =>  'long',
                        'nested_path'       =>  'last_appointment'
                    ]
                ];
            }


            if(request('order_by') == 'correspondence_language'){
                return [
                    "_script"=> [
                        "type"          => 'number',
                        "script"        =>  [
                            "lang"      =>  "painless",
                            "source"    =>  "int sortOrder = 0; if(params[doc['correspondence_language.keyword'].value] == null) {sortOrder =  1} else{sortOrder = params[doc['correspondence_language.keyword'].value]} sortOrder;",
                            "params"    => $this->getOrderLanaugeByUser()
                        ],
                        "order" =>  $order_direction
                    ],
                ];
            }


            if(request('order_by') == 'contact_record_status'){
                return [
                    "_script"=> [
                        "type"          => 'number',
                        "script"        =>  [
                            "lang"      =>  "painless",
                            "source"    =>  "int sortOrder = 0; if(params[doc['contact_record_status.keyword'].value] == null) {sortOrder =  1} else{sortOrder = params[doc['contact_record_status.keyword'].value]} sortOrder;",
                            "params"    => $this->getOrderControlRecordStatusByUser()
                        ],
                        "order" =>  $order_direction
                    ],
                ];
            }

        }

        return [
            'last_appointment.appointment_date'=> [
                'order'             =>  $order_direction,
                'unmapped_type'     =>  'long',
                'nested_path'       =>  'last_appointment'
            ]
        ];
    }

}
