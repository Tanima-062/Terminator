<?php

namespace App\Http\Controllers\Api\Web\Termin;

use OpenSearch\Client;
use App\Models\BrokerUser;
use App\Models\InternalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ContactDataRecords\GetAllTabDataOpenSearchQuery;
use App\Traits\ContactDataRecords\GetTerminDataOpenSearchQuery;
use App\Traits\ContactDataRecords\GetLeadTabDataOpenSearchQuery;
use App\Http\Requests\ContactDataRecord\AllocateContactDataRecordRequest;

class TerminAllocationController extends Controller
{
    use GetLeadTabDataOpenSearchQuery, GetTerminDataOpenSearchQuery, GetAllTabDataOpenSearchQuery;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {

        $request->validate([
            'contact_data_records'     =>  ['required', 'array', 'min:1',],
            'intermediary_user_id'    =>  ['required', 'exists:broker_users,id',],
        ]);

        // return $request->all();

        $auth_user = auth()->user();

        $broker_user = BrokerUser::find($request->intermediary_user_id);
        // $user_id = $broker_user->user_id;



        $data = [];
        $delete_ids = [];

        foreach($request->contact_data_records as $id => $value){
            if($value){
                $data[] = [
                    'allocate_by_user_id'       => $auth_user->id,
                    'contact_data_record_id'    =>  (int) $id,
                    'user_id'                   =>  $broker_user->user_id,
                    'broker_id'                 =>  null,
                    'broker_user_id'            =>  $broker_user->id,
                    'internal_user_id'          =>  null,
                    'organization_element_id'   =>  null,
                    'campaign_id'               =>  null,
                    'type'                      =>  'Broker User',
                ];
                $delete_ids[] = $id;
            }
        }


        $this->deleteOldRecords($delete_ids);

        //Assign new allocate records
        $auth_user->customerCompany->allocations()->createMany($data);

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
   public function getOptionData(Request $request)
   {
        $broker_user = BrokerUser::where('user_id',  auth()->user()->id)->first();

        $intermediary_users_lists = DB::table('broker_users')
                    ->join('users', 'broker_users.user_id', '=', 'users.id')
                    ->select('users.full_name as label', 'broker_users.id as value')
                    ->where('broker_users.customer_company_id', $broker_user->customer_company_id)
                    ->where('broker_users.broker_id', $broker_user->broker_id)
                    ->where('broker_users.role', BrokerUser::INTERMEDIARY)
                    ->orderBy('users.full_name', 'ASC')
                ->get()
                ;

        return response()->json([
            'intermediary_users_lists'              =>  $intermediary_users_lists,
        ]);
   }
}
