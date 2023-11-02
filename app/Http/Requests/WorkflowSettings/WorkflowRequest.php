<?php

namespace App\Http\Requests\WorkflowSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rules\In;

class WorkflowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        foreach (['process_lead_again', 'quality_check', 'arrange_appointment_call', 'status_not_confirmed', 'appointment_reminder_call', 'process_quality_topic', 'perform_quality_check', 'intermediary_feedback_capture'] as $value) {
            if ($this->hasPermission($value))
                return true;
        };

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,mixed>
     */
    public function rules()
    {
        return [
            'lead'                              => ['nullable', 'in:No Potential,Future Lead,Life Insurance,Car Insurance,Health Insurance,Not confirmed,Back to Call Agent', new RequiredIf($this->hasPermission('quality_check'))],
            'lead_control_task'                 => ['nullable', 'string', 'max:50', 'required_if:lead,Not confirmed,Back to Call Agent', new RequiredIf($this->hasPermission('quality_check') && $this->lead == 'Back to Call Agent')],
            'data_verified_updated'             => ['nullable', 'boolean', new RequiredIf($this->hasPermission('process_lead_again') && !$this->contact_record_status)],
            'contact_record_status'             => ['in:New-Not reached', new RequiredIf($this->hasPermission('process_lead_again') && !$this->data_verified_updated)],
            'remarks_control_lead'              => ['nullable', 'string', 'max:250'],
            'lead_control_task_status'          => ['boolean', 'nullable', new RequiredIf($this->hasPermission('status_not_confirmed'))],
            'lead_control_task_remarks'         => ['nullable', 'string', 'max:250', new RequiredIf($this->hasPermission('status_not_confirmed'))],
            'feedback'                          => ['nullable', 'in:Not Reached,Wrong Number,No Interest,Sick,Already terminated,Other Offer received,Call later,Appointment,No Potential', new RequiredIf($this->hasPermission('arrange_appointment_call') || $this->hasPermission('process_quality_topic'))],
            'feedback_remarks'                  => ['nullable', 'string', 'max:250'],
            'residential_address_confirmed'     => ['nullable', 'boolean'],
            'call_date'                         => ['nullable', new RequiredIf($this->hasPermission('arrange_appointment_call') && $this->feedback == 'Call later')],
            'call_time'                         => ['nullable', new RequiredIf($this->hasPermission('arrange_appointment_call') && $this->feedback == 'Call later')],
            'control_status_appointment'        => ['nullable', 'in:Back to call agent,Confirmed', new RequiredIf($this->hasPermission('perform_quality_check'))],
            'remarks_control_appointment'       => ['string', 'max:50', 'nullable'],
            'appointment_control_task'          => ['string', 'max:50', 'nullable', 'required_if:control_status_appointment,Back to call agent', new RequiredIf($this->hasPermission('perform_quality_check') && $this->control_status_appointment == 'Back to call agent')],
            'appointment_control_task_remarks'  => ['string', 'max:250', 'nullable', new RequiredIf($this->hasPermission('process_quality_topic') && $this->feedback == 'Appointment')],
            'appointment_control_task_status'   => ['boolean', 'nullable', new RequiredIf($this->hasPermission('process_quality_topic') && $this->feedback == 'Appointment')],
            'appointment_reminder_status'       => ['nullable', 'in:Done,Not reached - Appointment reminder,Cancelled', new RequiredIf($this->hasPermission('appointment_reminder_call'))],
            'appointment_reminder_remarks'      => ['nullable', 'string', 'max:250'],
            'appointment_took_place'            => ['boolean', 'nullable', new RequiredIf($this->hasPermission('intermediary_feedback_capture'))],
            'reason'                            => [new ExcludeIf($this->appointment_took_place === true && $this->outcome !== 'Negative'), 'nullable', new In($this->appointment_took_place == false ? ['Not at home', 'Untraceable', 'Not reachable', 'Cancellation on the part of intermediary', 'Cancelled'] : ['Treatment', 'Did not want an appointment', 'Multi-year contract', 'Other']),  new RequiredIf($this->hasPermission('intermediary_feedback_capture') && $this->appointment_took_place === false || ($this->appointment_took_place === true && $this->outcome == 'Negative'))],
            'outcome'                           => [new ExcludeIf($this->appointment_took_place == false), 'nullable', 'in:Positive,Negative,Follow up contact necessary', new RequiredIf($this->hasPermission('intermediary_feedback_capture') && $this->appointment_took_place == true)],
            'contracts_concluded'               => [new ExcludeIf(!($this->appointment_took_place == true && $this->outcome == 'Positive')), 'nullable', new RequiredIf($this->hasPermission('intermediary_feedback_capture') &&  $this->outcome == 'Positive' && $this->appointment_took_place == true)],
            'other'                             => [new ExcludeIf($this->appointment_took_place !== true || $this->outcome !== 'Negative' || $this->reason !== 'Other'), 'string', 'max:250', 'nullable'],
            'expiry_year'                       => [new ExcludeIf($this->reason !== 'Multi-year contract' || $this->appointment_took_place == false), 'nullable', new RequiredIf($this->hasPermission('intermediary_feedback_capture') && $this->reason == 'Multi-year contract')],
            'follow_up_contact_date'            => [new ExcludeIf($this->outcome !== 'Follow up contact necessary'), 'nullable', new RequiredIf($this->hasPermission('intermediary_feedback_capture') && $this->outcome == 'Follow up contact necessary' && $this->appointment_took_place === true)],
            'follow_up_contact_time'            => [new ExcludeIf($this->outcome !== 'Follow up contact necessary'), 'nullable', new RequiredIf($this->hasPermission('intermediary_feedback_capture') && $this->outcome == 'Follow up contact necessary' && $this->appointment_took_place === true)],
            'intermediary_remarks'              => ['nullable', 'string', 'max:250'],
        ];
    }

    private function hasPermission($permission)
    {
        $role = $this->user()->type == 'internal_user' ? $this->user()->internalUser->companyRole->name : $this->user()->type;
        $alignment = $this->user()->type == 'internal_user' ? $this->user()->alignmentUser->pluck('alignment_id') : [];

        switch ($permission) {
            case 'process_lead_again':
                return in_array($this->contact_data_record->category, ['lead_again', 'termination_lead']) && !$this->contact_data_record->data_verified_updated;
            case 'quality_check':
                return (
                    ($this->contact_data_record->contact_record_status == 'New' || $this->contact_data_record->contact_record_status == 'Completed') &&
                    ($this->contact_data_record->category == 'lead' || (in_array($this->contact_data_record->category, ['lead_again', 'termination_lead']) && $this->contact_data_record->data_verified_updated)) &&
                    (in_array($role, ['company_admin', 'Leader', 'Manager']) || ($role == 'Quality controller' && in_array('1', $alignment)))
                );

            case 'arrange_appointment_call':
                return (in_array(
                    $this->contact_data_record->contact_record_status,
                    ['Confirmed', 'Rund', 'Call later',  'Not Reached', 'Not reached - Appointment reminder', 'Appointment did not take place']
                ) &&
                    in_array(
                        $this->contact_data_record->category,
                        ['lead', 'lead_again', 'termination_lead', 'Appointment']
                    ) &&
                    (in_array($role, ['company_admin', 'Leader', 'Manager']) || ($role == 'Call agent' && in_array('2', $alignment)))
                );

            case 'perform_quality_check':
                return ($this->contact_data_record->category == 'Appointment' &&
                    in_array(
                        $this->contact_data_record->contact_record_status,
                        ['terminated', 'Quality topic solved']
                    ) &&
                    (in_array($role, ['company_admin', 'Leader', 'Manager']) || ($role == 'Quality controller' && in_array('2', $alignment)))
                );

            case 'process_quality_topic':
                return ($this->contact_data_record->category == 'lead' &&
                    $this->contact_data_record->contact_record_status == 'Quality Topic' &&
                    (in_array($role, ['company_admin', 'Leader', 'Manager']) || ($role == 'Call agent' && in_array('2', $alignment)))
                );

            case 'appointment_reminder_call':
                return ($this->contact_data_record->category == 'Appointment' &&
                    $this->contact_data_record->contact_record_status == 'Confirmed (Reminder pending)' &&
                    (in_array($role, ['company_admin', 'Leader', 'Manager']) || ($role == 'Quality controller' && in_array('2', $alignment)))
                );

            case 'status_not_confirmed':
                return $this->contact_data_record->contact_record_status == 'Not confirmed' && (in_array($role, ['company_admin', 'Leader', 'Manager']) || ($role == 'Call agent' && in_array('2', $alignment)));
            case 'intermediary_feedback_capture':
                return in_array($this->contact_data_record->contact_record_status, ['Allocated', 'Open']) && in_array($role, ['company_admin', 'Leader', 'Manager', 'Admin']) && $this->contact_data_record->category == 'Appointment';
            default:
                return false;
        }
    }
}
