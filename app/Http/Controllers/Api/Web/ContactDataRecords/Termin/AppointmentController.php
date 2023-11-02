<?php

namespace App\Http\Controllers\Api\Web\ContactDataRecords\Termin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreRequest;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordAppointment;
use App\Models\ContactDataRecordHistory;

class AppointmentController extends Controller
{


    public function store(StoreRequest $request, ContactDataRecord $contact_data_record)
    {
        $appointment = $contact_data_record->appointments()->create($request->validated() + ['customer_company_id' => $request->user()->customer_company_id, 'user_id' => $request->user()->id]);

        $contact_data_record->history()->create([
            'user_id' => $request->user('sanctum')->id,
            'action'  => ContactDataRecordHistory::APPOINTMENT_ENTRY,
            'notes'   => "$request->appointment_date $request->appointment_time $appointment->notes",
            'status_change' => false,
            'category_change' => false,
        ]);

        return $appointment;
    }
}
