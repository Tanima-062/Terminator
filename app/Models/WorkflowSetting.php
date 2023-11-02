<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowSetting extends Model
{
    use HasFactory;

    protected $fillable = ['call_attempt_limit', 'contact_limit', 'contact_data_record_limit', 'contact_record_creation_cost', 'contact_record_creation_revenue', 'data_verification_cost', 'data_verification_revenue', 'lead_quality_check_cost', 'lead_quality_check_revenue', 'edit_lead_quality_topics_cost', 'edit_lead_quality_topics_revenue', 'appointment_contact_cost', 'appointment_contact_revenue', 'appointment_quality_check_cost', 'appointment_quality_check_revenue', 'edit_appointment_quality_topics_cost', 'edit_appointment_quality_topics_revenue', 'carry_out_appointment_reminder_cost', 'carry_out_appointment_reminder_revenue'];
}
