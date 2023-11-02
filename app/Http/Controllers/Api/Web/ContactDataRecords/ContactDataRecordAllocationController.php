<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use App\Models\Broker;
use OpenSearch\Client;
use App\Models\Campaign;
use App\Models\BrokerUser;
use App\Models\InternalUser;
use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ContactDataRecords\GetLeadTabDataOpenSearchQuery;
use App\Http\Requests\ContactDataRecord\AllocateContactDataRecordRequest;
use App\Traits\ContactDataRecords\GetAllTabDataOpenSearchQuery;
use App\Traits\ContactDataRecords\GetTerminDataOpenSearchQuery;

class ContactDataRecordAllocationController extends Controller
{
    use GetLeadTabDataOpenSearchQuery, GetTerminDataOpenSearchQuery, GetAllTabDataOpenSearchQuery;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AllocateContactDataRecordRequest $request, Client $client)
    {
        $auth_user = auth()->user();
        $user_id = null;
        $broker_id = null;
        $broker_user_id = null;
        $internal_user_id = null;
        $organization_element_id = null;
        $type = null;

        // info($request->all());

        if($request->allocate_to == 'Broker'){
            $broker_id = $request->broker_id;
            $type = 'Broker';
        }else if($request->allocate_to == 'Broker User'){
            $broker_user_id = $request->broker_user_id;

            $broker_user = BrokerUser::find($broker_user_id);
            $user_id = $broker_user->user_id;
            $type = 'Broker User';
        }
        else if($request->allocate_to == 'Leader Head of'){
            $internal_user = InternalUser::find($request->leader_head_of_user_id);
            $user_id= $internal_user->user_id;
            $internal_user_id = $request->leader_head_of_user_id;
            $type = 'Leader Head of';
        }
        else if($request->allocate_to == 'Manager'){
            $internal_user = InternalUser::find($request->manager_in_user_id);
            $user_id= $internal_user->user_id;
            $internal_user_id = $request->manager_in_user_id;
            $type = 'Manager';
        }
        else if($request->allocate_to == 'Quality controller'){
            $internal_user = InternalUser::find($request->quality_controller_user_id);
            $user_id= $internal_user->user_id;
            $internal_user_id = $request->quality_controller_user_id;
            $type = 'Quality controller';
        }
        else if($request->allocate_to == 'Call agent'){
            $internal_user = InternalUser::find($request->call_agent_users_id);
            $user_id= $internal_user->user_id;
            $internal_user_id = $request->call_agent_users_id;
            $type = 'Call agent';
        }
        else {
            $organization_element_id = $request->allocate_to;
            $type = 'variableA';
        }


        $data = [];
        $delete_ids = [];


        if($request->type == 'manual_input'){
            $per_page = request('allocation_count', 0);

            $query= [];
            $manual_ids = [];

            if($request->tab == 'lead'){
                $query[] =  [
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

                $queryData = $this->getTerminRecords($client, 1, $per_page, $query);
                $manual_ids = $queryData['records'];
            }
            else if($request->tab == 'appointment'){
                $queryData = $this->getTerminRecords($client, 1, $per_page, $query);
                $manual_ids = $queryData['records'];
            }
            else if($request->tab == 'all'){
                $query[] =  [
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

                $query[] = [
                    'terms'=>[
                        'category.keyword'=>  [
                            'lead',
                        ]
                    ]
                ];

                $queryData = $this->getAllTabRecords($client, 1, $per_page, $query);

                // info($request->contact_data_records);
                // info($queryData);
                $manual_ids = $queryData['records'];
            }


            // info($request->tab);

            foreach($manual_ids as $id){
                $data[] = [
                    'allocate_by_user_id'       => $auth_user->id,
                    'contact_data_record_id'    =>  (int) $id,
                    'user_id'                   =>  $user_id,
                    'broker_id'                 =>  $broker_id,
                    'broker_user_id'            =>  $broker_user_id,
                    'internal_user_id'          =>  $internal_user_id,
                    'organization_element_id'   =>  $organization_element_id,
                    'campaign_id'               =>  $request->campaign_id,
                    'type'                      =>  $type,
                ];
                $delete_ids[] = $id;
            }
        }else {
            foreach($request->contact_data_records as $id => $value){
                if($value){
                    $data[] = [
                        'allocate_by_user_id'       => $auth_user->id,
                        'contact_data_record_id'    =>  (int) $id,
                        'user_id'                   =>  $user_id,
                        'broker_id'                 =>  $broker_id,
                        'broker_user_id'            =>  $broker_user_id,
                        'internal_user_id'          =>  $internal_user_id,
                        'organization_element_id'   =>  $organization_element_id,
                        'campaign_id'               =>  $request->campaign_id,
                        'type'                      =>  $type,
                    ];
                    $delete_ids[] = $id;
                }
            }
        }


        $this->deleteOldRecords($delete_ids);

        //Assign new allocate records
        $auth_user->customerCompany->allocations()->createMany($data);

        if($type == 'Broker' || $type == 'Broker User') {
            $contact_data_record_ids = array_keys($request->contact_data_records);
            ContactDataRecord::whereIn('id', $contact_data_record_ids)->whereIn('contact_record_status', [ContactDataRecord::STATUS_CONFIRMED, ContactDataRecord::STATUS_CONFIRMED_AND_REMINDED])->where('category', 'Appointment')->update(['contact_record_status' => ContactDataRecord::STATUS_ALLOCATED]);
        }

        return response()->json([
            'success'   =>  true
        ]);
    }



    /**
     * Delete Old Data
     *
     * @param array $delete_ids
     * @return void
     */
    public function deleteOldRecords($delete_ids)
    {
        //Delete old allocate records
        if(count($delete_ids)){
            // DB::table('contact_data_record_allocates')->whereIn('contact_data_record_id', $delete_ids)->delete();

            //Keep only last record
            foreach($delete_ids  as $id){
                DB::table('contact_data_record_allocates')->where('contact_data_record_id', $id)
                    ->orderBy('id','ASC')
                    ->havingRaw('count(*) > 1')
                    ->limit(1)
                    ->delete();
            }
        }
    }





    /**
     * Get contact data record allocation modal dropdown options data
     *
     * @param Request $request
     * @return void
     */
   public function getOptionData(Request $request, Client $client)
   {
    // return auth()->user()->contactDataRecords;

    // $client->update([
    //     'index' => 'contact_data_records',
    //     'id' => 18,
    //     'body' => [
    //         "doc"=> [
    //             'source'    =>  'not_online update',
    //             'category'  =>  'ok'
    //         ]
    //     ]
    // ]);
    // $contactDataRecord = ContactDataRecord::find(18);
    // $contactDataRecord->update([
    //     'category'  => 'termination_appointment'
    // ]);



    $auth_user = auth()->user();
        $internal_users = DB::table('internal_users')
                ->join('users', 'internal_users.user_id', '=', 'users.id')
                ->join('company_roles', 'internal_users.roles_id', '=', 'company_roles.id')
                ->select('company_roles.name as role_name', 'users.full_name as label', 'internal_users.id as value')
                ->where('internal_users.customer_company_id', $auth_user->customer_company_id)
                ->orderBy('users.full_name', 'ASC')
                ->get()
                ->groupBy('role_name')
            ;
        // info($internal_users);

        $broker_lists = DB::table('brokers')
            ->select('id as value', 'name as label')
            ->where('customer_company_id', $auth_user->customer_company_id)
            ->where('status', 'active')
            ->get();

        $broker_users_lists = DB::table('broker_users')
                    ->join('users', 'broker_users.user_id', '=', 'users.id')
                    ->select('users.full_name as label', 'broker_users.id as value')
                    ->where('broker_users.customer_company_id', $auth_user->customer_company_id)
                    ->orderBy('users.full_name', 'ASC')
                ->get()
                ;

        // return $auth_user;
        $organization_elements =  DB::table('organization_elements')
                        ->select('organization_elements.id as value', 'organization_elements.name as label')
                        ->join('hierarchy_elements', 'organization_elements.type_id', '=', 'hierarchy_elements.id')
                        ->whereNull('hierarchy_elements.hierarchy_level')
                        ->where('organization_elements.customer_company_id', $auth_user->customer_company_id)
                        ->where('organization_elements.status', 'Active')
                        ->get()
                        ->toArray()
            ;
        // return $organization_elements;
        $campaign_lists = Campaign::query()->select('id as value', 'name as label')->get();

        $allocates_to_lists = [
            ['label'=> 'Leader Head of', 'value'=>'Leader Head of'],
            ['label'=> 'Manager', 'value'=>'Manager'],
            ['label'=> 'Quality controller', 'value'=>'Quality controller'],
            ['label'=> 'Call agent', 'value'=>'Call agent'],
            ['label'=> 'Broker', 'value'=>'Broker'],
            ['label'=> 'Broker User', 'value'=>'Broker User'],
        ];

        return response()->json([
            'leader_head_of_users_lists'      =>  $internal_users['Leader']?? [],
            'manager_in_users_lists'          =>  $internal_users['Manager']?? [],
            'quality_controller_users_lists'  =>  $internal_users['Quality controller']?? [],
            'call_agent_users_lists'          =>  $internal_users['Call agent']?? [],
            'broker_lists'                    =>  $broker_lists,
            'broker_users_lists'              =>  $broker_users_lists,
            'allocate_to_lists'               =>  array_merge($allocates_to_lists, $organization_elements),
            'campaign_lists'                  =>  $campaign_lists,
        ]);
   }
}
