<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use App\Http\Controllers\Controller;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactDataRecordSetAppointmentLeadController extends Controller
{
    public function __invoke(Request $request)
    {
        foreach ($request->contact_data_records as $id => $value) {
            if ($value) {
                $contactDataRecord = ContactDataRecord::where('id', $id)->first();
                if ($contactDataRecord) {
                    DB::table('contact_data_record_allocates')->where('contact_data_record_id', $contactDataRecord->id)->delete();
                    DB::table('contact_data_record_appointments')->where('contact_data_record_id', $contactDataRecord->id)->delete();
                    DB::table('contact_data_record_feedback')->where('contact_data_record_id', $contactDataRecord->id)->delete();


                    $contactDataRecord
                        ->update([
                            'category'                  =>  'termination_lead',
                            'contact_record_status'     =>  'New',
                            'data_verified_updated'     =>  false
                        ]);

                    $history =  $contactDataRecord->contactDataRecordHistories()->create([
                        'user_id'   =>  auth()->user()->id,
                        'action'    =>  ContactDataRecordHistory::APPOINTMENT_LEAD,
                        'status_change' =>  true,
                        'old_status' =>  $contactDataRecord->contact_record_status,
                        'new_status' =>  'New',

                        'category_change'   =>  true,
                        'old_category'  => $contactDataRecord->category,
                        'new_category'  => 'termination_lead',
                    ]);
                }
            }
        }
    }
}
