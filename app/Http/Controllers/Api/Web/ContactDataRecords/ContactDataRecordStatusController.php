<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords;

use App\Http\Controllers\Controller;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactDataRecordStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ContactDataRecord $contactDataRecord)
    {
        $request->validate([
            'status' => "required|in:" . ContactDataRecord::STATUS_NEW . "," . ContactDataRecord::STATUS_INACTIVE,
        ]);

        if ($request->status == $contactDataRecord->contact_record_status) {
            throw ValidationException::withMessages(['status' => "Invalid data provided"]);
        }

        $contactDataRecord->history()->create([
            'user_id'            => $request->user('sanctum')->id,
            'action'             => $request->status == ContactDataRecord::STATUS_INACTIVE ? ContactDataRecordHistory::CONTACT_DATA_RECORD_DEACTIVATED : ContactDataRecordHistory::CONTACT_DATA_RECORD_ACTIVATED,
            'status_change'      => true,
            'old_status'         => $contactDataRecord->contact_record_status,
            'new_status'         => $request->status,
            'category_change'    => $request->status == ContactDataRecord::STATUS_NEW ? true : false,
            'old_category'      => $request->status == ContactDataRecord::STATUS_NEW ? $contactDataRecord->category : null,
            'new_category'      => $request->status == ContactDataRecord::STATUS_NEW ? 'lead_again' : null,
        ]);


        $input = ['contact_record_status' => $request->status];
        $request->status == ContactDataRecord::STATUS_NEW ? $input['category'] = 'lead_again' : null;

        $contactDataRecord->update($input);
        return $contactDataRecord;
    }
}
