<?php

namespace App\Models;

use App\Contracts\CompanyWisePrefixIDContract;
use App\Traits\CompanyWisePrefixID;
use App\Traits\ContactDataRecords\DropdownOption;
use Database\Factories\ContactDataRecordFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactDataRecord extends Model implements CompanyWisePrefixIDContract
{
    use HasFactory, DropdownOption, CompanyWisePrefixID;

    protected $fillable = [
        'campaign_id', 'customer_company_id', 'user_id', 'prefix_id', 'source', 'category', 'salutation', 'first_name', 'last_name', 'full_name', 'date_of_birth', 'phone_number', 'phone_number_iso_code', 'full_phone_number', 'email', 'street', 'house_number', 'zip_code', 'city', 'country_iso_code', 'canton', 'region', 'other_languages', 'correspondence_language', 'car_insurance', 'third_piller', 'household_goods', 'legal_protection', 'health_status', 'contact_person_for_insurance_questions', 'health_insurance', 'accident', 'franchise', 'supplementary_insurance', 'save', 'last_health_insurance_change', 'satisfaction', 'number_of_persons_in_household', 'work_activity', 'desired_consultation_channel', 'competition', 'origin_link', 'contact_desired', 'lead', 'remarks_control_lead', 'data_verified_updated', 'contact_record_status', 'not_reached_count', 'no_interest_count', 'new_not_reached_count', 'residential_address_confirmed'
    ];

    const OPENSEARCH_INDEX_NAME = 'contact_data_records';
    // const OPENSEARCH_INDEX_NAME = 'contact_data_records_cloud';

    const PREFIX = 'KD';
    const STATUS_NEW = 'New';
    const STATUS_NEW_NOT_REACHED = 'New-Not reached';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_DUPLICATE = 'Duplicate';
    const STATUS_CHECK_DUPLICATE = 'Check Duplicate';
    const STATUS_CONFIRMED = 'Confirmed';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_NOT_CONFIRMED = 'Not confirmed';
    const STATUS_CALL_LATER = 'Call later';
    const STATUS_NO_POTENTIAL = 'No Potential';
    const STATUS_FUTURE_LEAD = 'Future Lead';
    const STATUS_RUND = 'Rund';
    const STATUS_NOT_REACHED = 'Not Reached';
    const STATUS_TERMINATED = 'terminated';
    const STATUS_QUALITY_TOPIC_SOLVED = 'Quality topic solved';
    const STATUS_QUALITY_TOPIC = 'Quality Topic';
    const STATUS_OPEN = 'Open';
    const STATUS_ALLOCATED = 'Allocated';
    const STATUS_CONFIRMED_REMINDER_PENDING = 'Confirmed (Reminder pending)';
    const STATUS_CONFIRMED_AND_REMINDED = 'Confirmed &reminded';
    const STATUS_NOT_REACHED_APPOINTMENT_REMINDER = 'Not reached - Appointment reminder';
    const STATUS_POSITIVE_CONCLUDED = 'Positive concluded';
    const STATUS_NEGATIVE_CONCLUDED = 'Negative concluded';
    const STATUS_APPOINTMENT_DID_NOT_TAKE_PLACE = 'Appointment did not take place';



    protected $casts = [
        'other_languages'   =>  'array',
        'data_verified_updated'     =>  'boolean',
        'date_of_birth'             =>  'date',
    ];


    public static function getPrefix(): string
    {
        return self::PREFIX;
    }


    public function contactDataRecordHistories()
    {
        return $this->hasMany(ContactDataRecordHistory::class);
    }


    public function duplicates()
    {
        return $this->hasMany(ContactDataRecord::class, 'phone_number', 'phone_number');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function feedbacks()
    {
        return $this->hasMany(ContactDataRecordFeedback::class);
    }

    public function lastFeedback()
    {
        return $this->belongsTo(ContactDataRecordFeedback::class, 'last_feedback_id', 'id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(ContactDataRecordHistory::class, 'contact_data_record_id');
    }

    public function scopeLastFeedbackId($query)
    {
        $query->addSelect([
            'last_feedback_id' => ContactDataRecordFeedback::select('id')
                ->whereColumn('contact_data_record_feedback.contact_data_record_id', 'contact_data_records.id')
                // ->latest()
                ->orderBy('id', 'DESC')
                ->take(1)

        ]);
    }

    public function scopeWithLastFeedback($query)
    {
        $query->LastFeedbackId()->with('lastFeedback');
    }

    public function availability(): HasMany
    {
        return $this->hasMany(ContactDataRecordAvailability::class);
    }


    public function allocation()
    {
        return $this->hasOne(ContactDataRecordAllocate::class)
            ->latest();
    }

    public function lastAllocation()
    {
        return $this->hasOne(ContactDataRecordAllocate::class)
            ->oldest();
    }



    public function appointments()
    {
        return $this->hasMany(ContactDataRecordAppointment::class);
    }

    public function intermediaryFeedback()
    {
        return $this->hasOne(ContactDataRecordIntermediaryFeedback::class)->latest();
    }

    public function leadControlTasks()
    {
        return $this->hasMany(ContactDataRecordLeadControlTask::class);
    }

    public function lastAppointment()
    {
        return $this->belongsTo(ContactDataRecordAppointment::class, 'last_appointment_id', 'id');
    }

    public function cost(): Attribute
    {
        return Attribute::make(get: function () {
            $workflow_settings = WorkflowSetting::firstOrFail();
            $cost = 0;

            foreach ($this->history as $key => $history) {
                switch ($history->action) {
                    case ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION:
                        $cost += $workflow_settings->contact_record_creation_cost;
                        break;
                    case ContactDataRecordHistory::DATA_VERIFICATION_UPDATE:
                        $cost += $workflow_settings->data_verification_cost;
                        break;
                    case ContactDataRecordHistory::LEAD_QUALITY_CHECK:
                        $cost += $workflow_settings->lead_quality_check_cost;
                        break;
                    case ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL:
                        $cost += $workflow_settings->appointment_contact_cost;
                        break;
                    case ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK:
                        $cost += $workflow_settings->appointment_quality_check_cost;
                        break;
                    case ContactDataRecordHistory::APPOINTMENT_REMINDER:
                        $cost += $workflow_settings->carry_out_appointment_reminder_cost;
                        break;
                    default:
                        # code...
                        break;
                }
            }

            return $cost;
        });
    }

    public function revenue(): Attribute
    {
        return Attribute::make(get: function () {
            $workflow_settings = WorkflowSetting::firstOrFail();
            $cost = 0;

            foreach ($this->history as $key => $history) {
                switch ($history->action) {
                    case ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION:
                        $cost += $workflow_settings->contact_record_creation_revenue;
                        break;
                    case ContactDataRecordHistory::DATA_VERIFICATION_UPDATE:
                        $cost += $workflow_settings->data_verification_revenue;
                        break;
                    case ContactDataRecordHistory::LEAD_QUALITY_CHECK:
                        $cost += $workflow_settings->lead_quality_check_revenue;
                        break;
                    case ContactDataRecordHistory::APPOINTMENT_ARRANGEMENT_CALL:
                        $cost += $workflow_settings->appointment_contact_revenue;
                        break;
                    case ContactDataRecordHistory::APPOINTMENT_QUALITY_CHECK:
                        $cost += $workflow_settings->appointment_quality_check_revenue;
                        break;
                    case ContactDataRecordHistory::APPOINTMENT_REMINDER:
                        $cost += $workflow_settings->carry_out_appointment_reminder_revenue;
                        break;
                    default:
                        # code...
                        break;
                }
            }

            return $cost;
        });
    }

    public function profit(): Attribute
    {
        return Attribute::make(fn () => $this->revenue - $this->cost);
    }


    public function scopeLastAppointmentId($query)
    {
        $query->addSelect([
            'last_appointment_id' => ContactDataRecordAppointment::select('id')
                ->whereColumn('contact_data_record_appointments.contact_data_record_id', 'contact_data_records.id')
                // ->latest()
                ->orderBy('id', 'DESC')
                ->take(1)

        ]);
    }

    public function scopeWithLastAppointment($query)
    {
        $query->LastAppointmentId()->with('lastAppointment');
    }

    //WithLastAppointment
}
