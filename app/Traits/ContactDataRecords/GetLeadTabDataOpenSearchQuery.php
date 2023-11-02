<?php
namespace App\Traits\ContactDataRecords;

use Exception;
use OpenSearch\Client;
use Illuminate\Support\Carbon;
use App\Models\ContactDataRecord;

trait GetLeadTabDataOpenSearchQuery {
    use OrderByDirection;

    private $indexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;


    public function getLeadRecords(Client $client,  $current_page, $per_page, array $additional_queries = []){
         // return $this->getOrderLanaugeByUser();

        // return $this->getOrderLeadStatusByUser();
        // return $this->getOrderLastFeedbackByUser();

        // return $this->buildQuery();

        // info($this->buildQuery($per_page, $current_page, $additional_query));


        $search =  $client->search([
            'index' => $this->indexName,
            'body' => $this->buildLeadQuery($per_page, $current_page, $additional_queries)
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


    private function buildLeadQuery($per_page, $current_page, $additional_queries){
        $customer_company_id = auth()->user()->customer_company_id;

        $from = (($current_page - 1) * $per_page);


        $filters = [
            [
                'term'=>[
                    'customer_company_id'=> $customer_company_id
                ]
            ],
            [
                'terms'=>[
                    'category.keyword'=>  [
                        'termination_lead',
                        'future_lead',
                        'lead_again',
                        'lead',
                    ]
                ]
            ],
        ];


        if(request()->has('search') && !is_null(request('search'))){
            $filters[]  = [
                'multi_match'   =>   [
                    'query' =>  request('search', ""),
                    "type"=> "phrase_prefix",
                    'fields' => ['prefix_id', 'first_name', 'last_name', 'full_name', 'email', 'street', 'zip_code', 'canton', 'phone_number', 'full_phone_number']
                ]
            ];
        }
        if(request('start_date') && request('end_date')){
            try {
                $start_date = Carbon::parse(request('start_date'))->toDateString();
                $end_date = Carbon::parse(request('end_date'))->toDateString();

                $filters[]  = [
                    "range" => [
                        "created_at" => ["gte" => $start_date, "lte" => $end_date]
                    ],
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
        if(request('internal_users')){
            $internal_user_ids= explode(',', request('internal_users'));
            if(count($internal_user_ids)){
                 $filters[]  = [
                    "terms" => [
                        "allocation.user_id" => $internal_user_ids
                    ],
                ];
            }
        }
        if(request('sources')){
            $internal_user_ids= explode(',', request('sources'));
            if(count($internal_user_ids)){
                 $filters[]  = [
                    "terms" => [
                        "source" => $internal_user_ids
                    ],
                ];
            }
        }
        if(request('variableA')){
            $ids= explode(',', request('variableA'));
            if(count($ids)){
                 $filters[]  = [
                    "terms" => [
                        "allocation.organization_element_id" => $ids
                    ],
                ];
            }
        }
        if(request('year_start') && request('year_end')){
            try {
                $year_start =  Carbon::createFromFormat('Y', request('year_start'))->format('Y');
                $year_end = Carbon::createFromFormat('Y', request('year_end'))->format('Y');

                $filters[]  = [
                    "range" => [
                        "date_of_birth" => ["gte" => $year_start, "lte" => $year_end]
                    ],
                ];
            } catch (Exception $e) {
                // dd($e);
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
        if(request('regions')){
            $regions= explode(',', request('regions'));
            if(count($regions)){
                 $filters[]  = [
                    "terms" => [
                        "region.keyword" => $regions
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
        if(request('campaigns')){
            $campaigns= explode(',', request('campaigns'));
            if(count($campaigns)){
                 $filters[]  = [
                    "terms" => [
                        "campaign_id" => $campaigns
                    ],
                ];
            }
        }
        if(request('saves')){
            $saves= explode(',', request('saves'));
            if(count($saves)){
                 $filters[]  = [
                    "terms" => [
                        "save.keyword" => $saves
                    ],
                ];
            }
        }
        if(request('health_insurances')){
            $health_insurances= explode(',', request('health_insurances'));
            if(count($health_insurances)){
                 $filters[]  = [
                    "terms" => [
                        "health_insurance.keyword" => $health_insurances
                    ],
                ];
            }
        }
        if(request('third_pillers')){
            $third_pillers= explode(',', request('third_pillers'));
            if(count($third_pillers)){
                 $filters[]  = [
                    "terms" => [
                        "third_piller.keyword" => $third_pillers
                    ],
                ];
            }
        }
        if(request('contact_desireds')){
            $contact_desireds= explode(',', request('contact_desireds'));
            if(count($contact_desireds)){
                 $filters[]  = [
                    "terms" => [
                        "contact_desired.keyword" => $contact_desireds
                    ],
                ];
            }
        }
        if(request('leads')){
            $leads= explode(',', request('leads'));
            if(count($leads)){
                 $filters[]  = [
                    "terms" => [
                        "lead.keyword" => $leads
                    ],
                ];
            }
        }

        if(request('feedbacks')){
            $feedbacks= explode(',', request('feedbacks'));
            if(count($feedbacks)){
                 $filters[]  = [
                    "terms" => [
                        "feedbacks.keyword" => $feedbacks
                    ],
                ];
            }
        }
        if(request('duplicates')){
            $duplicates= explode(',', request('duplicates'));

            if(in_array('No Duplicate', $duplicates)){
                $filters[]= [
                    'bool'=> [
                        "must_not" => [
                            [
                                "terms" => [
                                    "contact_record_status.keyword" => ["Duplicate", "Check Duplicate"],
                                ],
                            ],
                        ],
                    ]
                ];
            }else {
                if(count($duplicates)){
                     $filters[]  = [
                        "terms" => [
                            "contact_record_status.keyword" => $duplicates
                        ],
                    ];
                }
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
            'sort'=> $this->generateLeadTabSortQuery(),
            // '_source'=>  ['user_id', 'prefix_id','last_feedback', 'first_name', 'last_name', 'canton',],
            // '_source'=>  ['contact_record_status'],
            '_source'=>  false,
            'track_total_hits'=> true
        ];
    }

    protected function generateLeadTabSortQuery(){

        $order_by = 'created_at';
        $order_direction = strtolower(request('direction')) == 'asc' ? 'asc': 'desc';

        if(request()->has('order_by') && !is_null(request('order_by'))) {
            if(in_array(request('order_by'), ['prefix_id', 'canton', 'first_name', 'last_name',  ])){
                $order_by = request('order_by').'.keyword';

                return [
                    $order_by  =>  [
                        'order' =>   $order_direction,
                        "missing"=> "_last"
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


            if(request('order_by') == 'lead'){
                return [
                    "_script"=> [
                        "type"          => 'number',
                        "script"        =>  [
                            "lang"      =>  "painless",
                            "source"    =>  "int sortOrder = 0; if(params[doc['lead.keyword'].value] == null) {sortOrder =  1} else{sortOrder = params[doc['lead.keyword'].value]} sortOrder;",
                            "params"    => $this->getOrderLeadStatusByUser()
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



            // if(in_array(request('order_by'), ['feedback', 'feedback_time',])){
            //     $order_by = request('order_by');
            //     $order_by = $order_by == 'feedback_time' ? 'created_at' : 'feedback'.'.keyword';

            //     return [
            //         'last_feedback.'.$order_by  => [
            //             'order' =>  $order_direction,
            //             'unmapped_type' =>  'long',
            //             "nested"=> [
            //                 "path"=> "last_feedback"
            //             ]
            //         ]
            //     ];

            // }
            if(request('order_by') == 'date_of_birth'){
                return [
                    'date_of_birth'  =>  [
                            'order' =>  $order_direction,
                            "missing"=> "_last"
                    ]
                ];
            }
            if(request('order_by') == 'last_feedback'){
                return [
                    "_script"=> [
                        "type"          => 'number',
                        "script"        =>  [
                            "lang"      =>  "painless",
                            "source"    =>  "int sortOrder = 0; if(params[doc['last_feedback.keyword'].value] == null) {sortOrder =  1} else{sortOrder = params[doc['last_feedback.keyword'].value]} sortOrder;",
                            "params"    => $this->getOrderLastFeedbackByUser()
                        ],
                        "order" =>  $order_direction
                    ],
                ];
            }

            if(request('order_by') == 'last_feedback_time'){
                return [
                    'last_feedback_time'  =>  [
                            'order' =>  $order_direction,
                            "missing"=> "_last"
                    ]
                ];
            }
            if(request('order_by') == 'created_at'){
                return [
                    'created_at'  =>  [
                            'order' =>  $order_direction,
                            "missing"=> "_last"
                    ]
                ];
            }
        }

        return [
            "_script"=> [
                "type"          => 'number',
                "script"        =>  [
                    "lang"      =>  "painless",
                    "source"    =>  "int sortOrder = 0; if(params[doc['contact_record_status.keyword'].value] == null) {sortOrder =  1} else{sortOrder = params[doc['contact_record_status.keyword'].value]} sortOrder;",
                    "params"    => [
                        'Check Duplicate'   =>  100,
                        'Duplicate'         =>  200,
                    ]
                ],
                "order" =>  'desc'
            ],
            'id'  =>  [
                'order' =>   'desc',
                "missing"=> "_last"
            ],
        ];

    }
}
