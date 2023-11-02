<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactDataRecordHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',  'contact_data_record_id', 'action', 'status_change', 'old_status', 'new_status', 'category_change', 'old_category', 'new_category', 'user_type', 'notes', 'meta'
    ];

    const CONTACT_DATA_RECORD_CREATION = 'Contact record creation';
    const POSSIBLE_DUPLICATED_DETECTED = 'Possible duplicate detected';
    const APPOINTMENT_ENTRY = 'Appointment Entry';
    const CONTACT_DATA_RECORD_DEACTIVATED = 'Contact data record deactivated';
    const CONTACT_ATTEMPT_LIMIT_REACHED = 'Contact attempt limit reached';
    const DATA_VERIFICATION_UPDATE = 'Data verification/update';
    const LEAD_QUALITY_CHECK = 'Lead Quality Check';
    const APPOINTMENT_ARRANGEMENT_CALL = 'Appointment-Arrangement Call';
    const APPOINTMENT_AGREED = 'Appointment Agreed';
    const APPOINTMENT_QUALITY_CHECK = 'Appointment Quality Check';
    const APPOINTMENT_REMINDER = 'Appointment Reminder';
    const APPOINTMENT_LEAD = 'Appointment Lead';
    const CONTACT_ATTEMPT = 'Contact attempt';
    const CONTACT_DATA_RECORD_ACTIVATED = 'Contact data record activated';
    const LEAD_AGAIN = 'Lead again';
    const LEAD_CONTROL_TASK_CAPTURE = 'Lead Control Task Capture';
    const LEAD_CONTROL_TASK_EDITED = 'Lead Control Task edited';
    const LATER_APPOINTMENT_ARRANGEMENT_CALL = 'Later Appointment-Arrangement Call';
    const APPOINTMENT_CONTROL_TASK_CAPTURE = 'Appointment Control Task Capture';
    const APPOINTMENT_CONTROL_TASK_EDITED = 'Appointment Control Task edited';
    const APPOINTMENT_FEEDBACK = 'Appointment Feedback';
    const ALLOCATION = 'Allocation';
    const DUPLICATE = 'Duplicate';
    const NO_DUPLICATE = 'No duplicate';


    const STATUS_NEW = "new";
    const STATUS_OPEN = "Open";
    const STATUS_APPOINMENT_NOT_TAKE_PLACE = "Appointment didn’t take place";
    const STATUS_POSITIVE_COMPLETED = 'Positive completed';
    const STATUS_NEGATIVE_COMPLETED = 'Negative completed';

    protected $casts = [
        'status_change'     =>  'boolean',
        'category_change'   =>  'boolean',
        'meta'              =>  'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
