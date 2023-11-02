<?php

namespace App\Http\Controllers\Api\Web\WorkflowSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkflowSettings\WorkflowRequest;
use App\Imports\ContactDataRecords\ContactDataRecordImport;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordAppointment;
use App\Models\ContactDataRecordFeedback;
use App\Models\ContactDataRecordHistory;
use App\Models\ContactDataRecordLeadControlTask;
use App\Models\WorkflowSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WorkflowController extends Controller
{
    public function __invoke(WorkflowRequest $request, ContactDataRecord $contact_data_record)
    {
        $action = null;
        $notes = null;
        $workfow_limits = WorkflowSetting::firstOrFail();
        $not_reached_limit = $workfow_limits->call_attempt_limit;
        $no_interest_limit = $workfow_limits->contact_limit;

        //process lead again
        if ($request->data_verified_updated) {
            $contact_data_record->fill(['data_verified_updated' => 1, 'contact_record_status' => ContactDataRecord::STATUS_NEW]);
            $action = ContactDataRecordHistory::DATA_VERIFICATION_UPDATE;
        }

        if ($request->contact_record_status == ContactDataRecord::STATUS_NEW_NOT_REACHED) {
            if ($not_reached_limit <= $contact_data_record->new_not_reached_count) {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_FUTURE_LEAD, 'category' => 'future_lead', 'new_not_reached_count' => $contact_data_record->new_not_reached_count + 1]);
                $action = ContactDataRecordHistory::CONTACT_ATTEMPT_LIMIT_REACHED;
            } else {
                $contact_data_record->fill(['new_not_reached_count' => $contact_data_record->new_not_reached_count + 1, 'contact_record_status' => ContactDataRecord::STATUS_NEW_NOT_REACHED]);
                $action = ContactDataRecordHistory::CONTACT_ATTEMPT;
            }
        }

        // perform quality check
        if ($request->lead == 'No Potential') {
            $contact_data_record->fill(['lead' => $request->lead, 'remarks_control_lead' => $request->remarks_control_lead, 'contact_record_status' => 'No Potential', 'category' => 'no_potential']);
        }

        if ($request->lead == 'Future Lead') {
            $contact_data_record->fill(['lead' => $request->lead, 'remarks_control_lead' => $request->remarks_control_lead, 'contact_record_status' => 'Future Lead', 'category' => 'future_lead']);
        }

        if ($request->lead == 'Life Insurance' || $request->lead == 'Health Insurance' || $request->lead == 'Car Insurance') {
            $contact_data_record->fill(['lead' => $request->lead, 'remarks_control_lead' => $request->remarks_control_lead, 'contact_record_status' => ContactDataRecord::STATUS_CONFIRMED]);
        }

        if ($request->lead == 'Back to Call Agent' || $request->lead == 'Not confirmed') {
            $contact_data_record->fill(['lead' => $request->lead, 'remarks_control_lead' => $request->remarks_control_lead, 'contact_record_status' => 'Not confirmed']);
            $contact_data_record->leadControlTasks()->create([
                'lead_control_task' => $request->lead_control_task
            ]);
        }

        if ($request->lead) {
            $action = ContactDataRecordHistory::LEAD_QUALITY_CHECK;
            $notes = $request->remarks_control_lead;
        }


        if ($request->lead_control_task_status && $request->lead_control_task_remarks && $contact_data_record->leadControlTasks()->where('lead_control_task_status', false)->count()) {
            $lead_control_task = $contact_data_record->leadControlTasks()->first();
            $lead_control_task->update([
                'lead_control_task_status' => true,
                'lead_control_task_remarks' => $request->remarks,
            ]);
            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_COMPLETED]);

            $action = ContactDataRecordHistory::LEAD_CONTROL_TASK_EDITED;
            $notes = $request->lead_control_task_remarks;
        }

        if ($request->feedback == ContactDataRecordFeedback::CALL_LATER) {
            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_CALL_LATER]);
            $this->createOrUpdateFeedback($contact_data_record);

            $action = ContactDataRecordHistory::LATER_APPOINTMENT_ARRANGEMENT_CALL;
        }

        if ($request->feedback == ContactDataRecordFeedback::WRONG_NUMBER || $request->feedback == ContactDataRecordFeedback::NO_POTENTIAL || $request->feedback == ContactDataRecordFeedback::SICK) {
            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_NO_POTENTIAL, 'category' => 'no_potential']);
            $this->createOrUpdateFeedback($contact_data_record);
        }

        if ($request->feedback == ContactDataRecordFeedback::ALREADY_TERMINATED) {
            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_FUTURE_LEAD, 'category' => 'future_lead']);
            $this->createOrUpdateFeedback($contact_data_record);
        }

        if ($request->feedback == ContactDataRecordFeedback::OTHER_OFFER_RECEIVED || $request->feedback == ContactDataRecordFeedback::NO_INTEREST) {
            if ($no_interest_limit <= $contact_data_record->no_interest_count) {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_FUTURE_LEAD, 'category' => 'future_lead', 'no_interest_count' => $contact_data_record->no_interest_count + 1]);
            } else {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_RUND, 'no_interest_count' => $contact_data_record->no_interest_count + 1]);
            }
            $this->createOrUpdateFeedback($contact_data_record);
        }
        if ($request->feedback == ContactDataRecordFeedback::NOT_REACHED) {
            if ($not_reached_limit <= $contact_data_record->not_reached_count) {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_FUTURE_LEAD, 'category' => 'future_lead', 'not_reached_count' => $contact_data_record->not_reached_count + 1]);
            } else {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_NOT_REACHED, 'not_reached_count' => $contact_data_record->not_reached_count + 1]);
            }
            $this->createOrUpdateFeedback($contact_data_record);
        }

        if ($request->feedback == ContactDataRecordFeedback::APPOINTMENT) {

            $appointment = $contact_data_record->appointments()->latest()->first();
            if (!$appointment || Carbon::parse("$appointment->appointment_date $appointment->appointment_time")->lessThanOrEqualTo(now()) || $appointment->appointment_control_task_status) {
                throw ValidationException::withMessages(['appointment' => 'No Valid Appointment Available']);
            }

            $contact_data_record->fill(['contact_record_status' => $contact_data_record->contact_record_status == ContactDataRecord::STATUS_QUALITY_TOPIC ? ContactDataRecord::STATUS_QUALITY_TOPIC_SOLVED : ContactDataRecord::STATUS_TERMINATED, 'category' => 'Appointment']);

            if ($contact_data_record->contact_record_status == ContactDataRecord::STATUS_QUALITY_TOPIC_SOLVED) {
                $appointment->update(['appointment_control_task_status' => true, 'appointment_control_task_remarks' => $request->appointment_control_task_remarks]);

                $action = ContactDataRecordHistory::APPOINTMENT_CONTROL_TASK_EDITED;
            } else {
                $action = ContactDataRecordHistory::APPOINTMENT_AGREED;
            }
            $this->createOrUpdateFeedback($contact_data_record);
        }

        if($request->has('feedback')) {
            $contact_data_record->fill(['residential_address_confirmed' => $request->residential_address_confirmed ? true : false]);
        }

        if ($request->control_status_appointment == ContactDataRecordAppointment::CONFIRMED) {
            $appointment = $contact_data_record->appointments()->latest()->first();
            if (!$appointment || Carbon::parse("$appointment->appointment_date $appointment->appointment_time")->lessThanOrEqualTo(now())) {
                throw ValidationException::withMessages(['appointment' => 'No Valid Appointment Available']);
            }

            if (Carbon::parse("$appointment->appointment_date $appointment->appointment_time")->lessThanOrEqualTo(now()->addDays(3))) {
                $appointment->update(['control_status_appointment' => $request->control_status_appointment, 'remarks_control_appointment' => $request->remarks_control_appointment]);
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_CONFIRMED]);
            } else {
                $appointment->update(['control_status_appointment' => ContactDataRecordAppointment::CONFIRMED_REMINDER_PENDING, 'remarks_control_appointment' => $request->remarks_control_appointment]);
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_CONFIRMED_REMINDER_PENDING]);
            }
        }

        if ($request->control_status_appointment == ContactDataRecordAppointment::BACK_TO_CALL_AGENT) {
            $appointment = $contact_data_record->appointments()->latest()->first();
            if (!$appointment || Carbon::parse("$appointment->appointment_date $appointment->appointment_time")->lessThanOrEqualTo(now()) || $appointment->appointment_control_task_status) {
                throw ValidationException::withMessages(['appointment' => 'No Valid Appointment Available']);
            }

            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_QUALITY_TOPIC, 'category' => "lead"]);
            $appointment->update(['control_status_appointment' => ContactDataRecordAppointment::BACK_TO_CALL_AGENT, 'appointment_control_task' => $request->appointment_control_task]);
        }


        if ($request->control_status_appointment) {
            $action = ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK;
            $notes = $request->remarks_control_appointment;
        }

        if ($request->appointment_reminder_status == ContactDataRecordAppointment::REMINDER_DONE) {
            $appointment = $contact_data_record->appointments()->latest()->first();
            $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_CONFIRMED_AND_REMINDED]);
            $appointment->update(['control_status_appointment' => ContactDataRecordAppointment::CONFIRMED_REMINDED, 'appointment_reminder_status' => $request->appointment_reminder_status,  'appointment_reminder_remarks' => $request->appointment_reminder_remarks]);
        }

        if ($request->appointment_reminder_status == ContactDataRecordAppointment::NOT_REACHED_APPOINTMENT_REMINDER) {
            $appointment = $contact_data_record->appointments()->latest()->first();
            if ($not_reached_limit <= $contact_data_record->not_reached_count) {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_FUTURE_LEAD, 'category' => 'future_lead', 'not_reached_count' => $contact_data_record->not_reached_count + 1]);
            } else {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_NOT_REACHED_APPOINTMENT_REMINDER, 'not_reached_count' => $contact_data_record->not_reached_count + 1, 'category' => 'termination_lead']);
            }
            $appointment->update(['control_status_appointment' => ContactDataRecordAppointment::NOT_REACHED_APPOINTMENT_REMINDER, 'appointment_reminder_status' => $request->appointment_reminder_status, 'appointment_reminder_remarks' => $request->appointment_reminder_remarks]);
        }

        if ($request->appointment_reminder_status == ContactDataRecordAppointment::CANCELLED) {
            $appointment = $contact_data_record->appointments()->latest()->first();
            $contact_data_record->fill(['control_status_appointment' => ContactDataRecord::STATUS_FUTURE_LEAD, 'category' => 'future_lead']);
            $appointment->update(['appointment_reminder_status' => $request->appointment_reminder_status, 'appointment_reminder_status' => $request->appointment_reminder_remarks]);
        }

        if ($request->appointment_reminder_status) {
            $action = ContactDataRecordHistory::APPOINTMENT_REMINDER;
            $notes = $request->appointment_reminder_status;
        }

        if ($request->appointment_took_place !== null) {
            $this->createOrUpdateIntermediaryFeedback($contact_data_record, $request);
            if ($request->appointment_took_place === false) {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_APPOINTMENT_DID_NOT_TAKE_PLACE]);
            }
            if ($request->appointment_took_place === true && $request->outcome == "Positive") {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_POSITIVE_CONCLUDED]);
            }
            if ($request->appointment_took_place === true && $request->outcome == "Negative") {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_NEGATIVE_CONCLUDED]);
            }
            if ($request->appointment_took_place === true && $request->outcome == "Follow up contact necessary") {
                $contact_data_record->fill(['contact_record_status' => ContactDataRecord::STATUS_OPEN]);
            }
            $action = ContactDataRecordHistory::APPOINTMENT_FEEDBACK;
            $notes = $request->intermediary_remarks;
        }

        if ($action) {
            $contact_data_record->history()->create([
                'user_id'           => $request->user()->id,
                'category_change'   => $contact_data_record->isDirty('category'),
                'status_change'     => $contact_data_record->isDirty('contact_record_status'),
                'action'            => $action,
                'notes'             => $notes,
                'old_category'      => $contact_data_record->isDirty('category') ? $contact_data_record->getOriginal('category') : null,
                'new_category'      => $contact_data_record->isDirty('category') ? $contact_data_record->category : null,
                'old_status'        => $contact_data_record->isDirty('contact_record_status') ? $contact_data_record->getOriginal('contact_record_status') : null,
                'new_status'        => $contact_data_record->isDirty('contact_record_status') ? $contact_data_record->contact_record_status : null,
            ]);
        }

        $contact_data_record->save();
        //If broker or broker users allocation is available and contact_record_status is Confirmed or Confiremd Reminder Done
        if (($contact_data_record->contact_record_status == ContactDataRecord::STATUS_CONFIRMED || $contact_data_record->contact_record_status == ContactDataRecord::STATUS_CONFIRMED_AND_REMINDED) && $contact_data_record->category == 'Appointment') {
            $allocationsCount = $contact_data_record->allocation()->where(fn($q) => $q->whereNotNull('broker_id')->orWhereNotNull('broker_user_id'))->count();
            if (!$allocationsCount) {
                return;
            }
            $contact_data_record->history()->create([
                'user_id'           => $request->user()->id,
                'category_change'   => false,
                'status_change'     => true,
                'action'            => ContactDataRecordHistory::ALLOCATION,
                'old_status'        => $contact_data_record->contact_record_status,
                'new_status'        => ContactDataRecord::STATUS_ALLOCATED,
            ]);
            $contact_data_record->update(['contact_record_status' => ContactDataRecord::STATUS_ALLOCATED]);
        }
    }

    private function createOrUpdateFeedback(ContactDataRecord $contact_data_record)
    {
        $request = request();
        if ($feedback = $contact_data_record->feedbacks()->first()) {
            $feedback->update([
                'feedback'          => $request->feedback,
                'feedback_remarks'  => $request->feedback_remarks,
                'call_date'         => $request->feedback == ContactDataRecordFeedback::CALL_LATER ? $request->call_date : $feedback->call_date,
                'call_time'         => $request->feedback == ContactDataRecordFeedback::CALL_LATER ? $request->call_time : $feedback->call_time
            ]);
        } else {
            $contact_data_record->feedbacks()->create([
                'feedback'          => $request->feedback,
                'feedback_remarks'  => $request->feedback_remarks,
                'call_date'         => $request->feedback == ContactDataRecordFeedback::CALL_LATER ? $request->call_date : null,
                'call_time'         => $request->feedback == ContactDataRecordFeedback::CALL_LATER ? $request->call_time : null
            ]);
        }
    }

    private function createOrUpdateIntermediaryFeedback(ContactDataRecord $contact_data_record, WorkflowRequest $request)
    {
        if ($intermediaryFeedback = $contact_data_record->intermediaryFeedback) {
            $intermediaryFeedback->update($request->validated());
        } else {
            $contact_data_record->intermediaryFeedback()->create($request->validated());
        }
    }
}
