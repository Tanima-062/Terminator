<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords\Termin;

use Exception;
use App\Models\User;
use OpenSearch\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ContactDataRecord;
use App\Http\Controllers\Controller;
use App\Traits\ContactDataRecords\OrderByDirection;
use App\Http\Resources\ContactDataRecords\TerminTabResource;
use App\Models\CompanyRole;
use App\Traits\ContactDataRecords\GetTerminDataOpenSearchQuery;

class GetTerminContactDataRecordController extends Controller
{
    use GetTerminDataOpenSearchQuery;

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


        $auth_user = auth()->user();
        $query = [];
        if($auth_user->type == User::INTERNAL_USER){
            // dd($auth_user->internal_user);
            $internal_user = $auth_user->internalUser;

            if($internal_user->companyRole->name == CompanyRole::LEADER || $internal_user->companyRole->name == CompanyRole::MANAGER){
                $query[] =  [
                    "bool"  => [
                        "should" => [
                            [
                                'term'=>[
                                    "allocation.user_id" => $auth_user->id
                                ]
                            ],
                        ]
                    ]
                ];
            }
           if($internal_user->companyRole->name == CompanyRole::CALL_AGENT) {
                $query[] =  [
                    // "term" => [
                    //     "allocation.user_id" => $auth_user->id
                    // ],
                        "bool"  => [
                            "should" => [
                                [
                                    'term'=>[
                                        "allocation.user_id" => $auth_user->id
                                    ]
                                ],
                                // [
                                //     'term'=>[
                                //         "user_id" => $auth_user->id
                                //     ]
                                // ],
                            ]
                        ]

                ];
           }
           if($internal_user->companyRole->name == CompanyRole::QUALITY_CONTROLLER) {
                $query[] =  [
                        "bool"  => [
                            "should" => [
                                [
                                    'term'=>[
                                        "allocation.user_id" => $auth_user->id
                                    ]
                                ],
                            ]
                        ]

                ];
           }
        }

        $queryData = $this->getTerminRecords($client, $current_page, $per_page, $query);
        $records = $queryData['records'];
        $total = $queryData['total'];

        // return $records;
        $contactDataRecords = ContactDataRecord::query()
            ->with(['campaign:id,name', 'allocation'=>function($q){
                $q->select(['id','contact_data_record_id', 'user_id','broker_user_id','internal_user_id', 'organization_element_id', 'broker_id', 'type']);
                $q->with(['user:id,full_name', 'broker:id,name', 'organizationElement:id,name']);
            }])
            ->select(['id', 'campaign_id', 'correspondence_language', 'canton', 'first_name', 'last_name', 'zip_code', 'city', 'number_of_persons_in_household', 'contact_record_status'])
            ->WithLastAppointment()
            ->whereIn('id', $records)
            ->when(count($records), fn($q)=>$q->orderByRaw("field(id," . implode(',', $records) . ")"))
            ->get()
            ;

        // return $contactDataRecords;
        return response([
            'data'  =>  TerminTabResource::collection($contactDataRecords),
            'meta'  =>  customPagination($total, $per_page)
        ]);

        return TerminTabResource::collection($contactDataRecords);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ContactDataRecord $contactDataRecord)
    {
        //
    }




}
