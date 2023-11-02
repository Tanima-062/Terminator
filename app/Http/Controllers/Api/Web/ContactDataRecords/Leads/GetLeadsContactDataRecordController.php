<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords\Leads;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use OpenSearch\Client;
use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ContactDataRecords\OrderByDirection;
use App\Exports\ContactDataRecords\LeadTabDataExport;
use App\Mail\ContactDataRecords\LeadsTabDataExportEmail;
use App\Http\Resources\ContactDataRecords\LeadsTabResource;
use App\Models\CompanyRole;
use App\Traits\ContactDataRecords\GetLeadTabDataOpenSearchQuery;

class GetLeadsContactDataRecordController extends Controller
{
    use GetLeadTabDataOpenSearchQuery;



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        // return LeadsTabResource::collection($contactDataRecords);
        if (!env('OPENSEARCH_ENABLED')) {
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
                                [
                                    'term'=>[
                                        "user_id" => $auth_user->id
                                    ]
                                ],
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
                                // [
                                //     'term'=>[
                                //         "user_id" => $auth_user->id
                                //     ]
                                // ],
                            ]
                        ]

                ];
           }
        }



        $queryData = $this->getLeadRecords($client, $current_page, $per_page, $query);
        $records = $queryData['records'];
        $total = $queryData['total'];


        // return $records;
        $contactDataRecords = ContactDataRecord::query()
            ->with(['campaign:id,name', 'creator:id,full_name,customer_company_id', 'creator.customerCompanyAdmin:id,user_id', 'allocation' => function ($q) {
                $q->select(['id', 'contact_data_record_id', 'user_id', 'broker_user_id', 'internal_user_id', 'organization_element_id', 'broker_id', 'type']);
                $q->with(['user:id,full_name', 'broker:id,name', 'organizationElement:id,name']);
            }])
            ->select([
                'id', 'prefix_id', 'created_at', 'user_id', 'campaign_id', 'correspondence_language',
                'canton', 'first_name', 'last_name', 'lead', 'contact_record_status', 'date_of_birth'
            ])
            ->WithLastFeedback()
            ->whereIn('id', $records)
            ->when(count($records), fn ($q) => $q->orderByRaw("field(id," . implode(',', $records) . ")"))
            ->get();

        return response([
            'data'  =>  LeadsTabResource::collection($contactDataRecords),
            'meta'  =>  customPagination($total, $per_page)
        ]);

        return LeadsTabResource::collection($contactDataRecords);
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
