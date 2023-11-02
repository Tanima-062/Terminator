<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactDataRecordIntermediaryFeedback extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_took_place', 'outcome', 'contracts_concluded', 'intermediary_remarks', 'reason', 'other', 'expiry_year', 'follow_up_contact_date', 'follow_up_contact_time', 'contact_data_record_id'];

    public function contactDataRecord(): BelongsTo
    {
        return $this->belongsTo(ContactDataRecord::class);
    }
}
