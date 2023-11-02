<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDataRecordFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_data_record_id', 'feedback', 'feedback_remarks', 'call_time', 'call_date'
    ];

    const NOT_REACHED = 'Not Reached';
    const WRONG_NUMBER = 'Wrong Number';
    const NO_INTEREST = 'No Interest';
    const SICK = 'Sick';
    const ALREADY_TERMINATED = 'Already terminated';
    const OTHER_OFFER_RECEIVED = 'Other Offer received';
    const CALL_LATER = 'Call later';
    const APPOINTMENT = 'Appointment';
    const NO_POTENTIAL = 'No Potential';



    public function contactDataRecord()
    {
        return $this->belongsTo(ContactDataRecord::class);
    }



    public static function getAllFeedbackLists()
    {
        return [
            self::NOT_REACHED,
            self::WRONG_NUMBER,
            self::NO_INTEREST,
            self::SICK,
            self::ALREADY_TERMINATED,
            self::OTHER_OFFER_RECEIVED,
            self::CALL_LATER,
            self::APPOINTMENT,
            self::NO_POTENTIAL
        ];
    }
}
