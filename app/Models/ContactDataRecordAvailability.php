<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDataRecordAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_data_record_id', 'day', 'first_start_time', 'first_end_time', 'last_start_time', 'last_end_time'
    ];
}
