<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeviceAuthAndHierarchyElementRole extends Model
{
    use HasFactory;

    const RESPONSIBLE_ROLE = 'responsible';
    const DIRECT_SUBORDINATE_ROLE = 'direct_subordinate';

    protected $fillable = ['role_type', 'company_role_id', 'roleable_id', 'roleable_type'];


    public function roleable()
    {
        return $this->morphTo('roleable');
    }

    public function scopeDeviceAuth($q)
    {
        return $q->where('roleable_type', 'App\Models\CustomerCompany');
    }
    public function scopeHierarchyElement($q)
    {
        return $q->where('roleable_type', 'App\Models\HierarchyElement');
    }

    public function companyRole():  BelongsTo
    {
        return $this->belongsTo(CompanyRole::class, 'company_role_id');
    }
}
