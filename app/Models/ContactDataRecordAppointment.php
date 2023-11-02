<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\CompanyWisePrefixIDContract;
use App\Traits\CompanyWisePrefixID;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactDataRecordAppointment extends Model implements CompanyWisePrefixIDContract
{
    use HasFactory, CompanyWisePrefixID;

    const CONFIRMED = 'Confirmed';
    const BACK_TO_CALL_AGENT = 'Back to call agent';
    const CONFIRMED_REMINDER_PENDING = 'Confirmed, reminder pending';
    const CONFIRMED_REMINDED = 'Confirmed &reminded';
    const NOT_REACHED_APPOINTMENT_REMINDER = 'Not reached - Appointment reminder';

    const REMINDER_DONE = 'Done';
    const NOT_REACHED = 'Not reached';
    const CANCELLED = 'Cancelled';

    protected $fillable = [
        'prefix_id', 'customer_company_id', 'contact_data_record_id', 'user_id', 'appointment_date', 'appointment_time', 'notes', 'control_status_appointment', 'appointment_control_task', 'appointment_control_task_status', 'appointment_reminder_remarks', 'appointment_reminder_status', 'appointment_control_task_remarks'
    ];

    const PREFIX = 'TM';

    public static function getPrefix(): string
    {
        return self::PREFIX;
    }


    public function customerCompany()
    {
        return $this->belongsTo(CustomerCompany::class);
    }

    public function contactDataRecord()
    {
        return $this->belongsTo(ContactDataRecord::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
