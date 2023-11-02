<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use Illuminate\Http\Request;
use App\Models\ContactDataRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ContactDataRecordHistory;

class ContactDataRecordSetLeadAgainController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        foreach($request->contact_data_records as $id => $value){
            if($value){
                $contactDataRecord = ContactDataRecord::where('id', $id)->first();
                if($contactDataRecord){
                        DB::table('contact_data_record_allocates')->where('contact_data_record_id', $contactDataRecord->id)->delete();
                        DB::table('contact_data_record_appointments')->where('contact_data_record_id', $contactDataRecord->id)->delete();
                        DB::table('contact_data_record_feedback')->where('contact_data_record_id', $contactDataRecord->id)->delete();


                        $contactDataRecord
                        ->update([
                            'category'                  =>  'lead_again',
                            'contact_record_status'     =>  'New',
                            'data_verified_updated'     =>  false
                        ]);

                        // info('update',[ $id]);

                        $history =  $contactDataRecord->contactDataRecordHistories()->create([
                            'user_id'   =>  auth()->user()->id,
                            'action'    =>  ContactDataRecordHistory::LEAD_AGAIN,
                            'status_change' =>  true,
                            'old_status' =>  $contactDataRecord->contact_record_status,
                            'new_status' =>  'New',

                            'category_change'   =>  true,
                            'old_category'  => $contactDataRecord->category,
                            'new_category'  => 'lead_again',
                        ]);
                        //contact_data_record_allocates
                        //contact_data_record_appointments
                        //contact_data_record_feedback


                        //feedbacks
                        //allocation
                        //appointments
                }
            }
        }
    }


}
