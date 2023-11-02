<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDataRecordLeadControlTask extends Model
{
    use HasFactory;

    protected $fillable = ['lead_control_task_status', 'lead_control_task_remarks', 'lead_control_task'];

    public function contactDataRecord()
    {
        return $this->belongsTo(ContactDataRecord::class);
    }
}
