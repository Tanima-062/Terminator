<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class HierarchyElement extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';

    protected $fillable = ['name', 'hierarchy_level', 'status', 'updated_at', 'created_at'];
    protected $with = ['responsibleRoles', 'directSubordinateRoles'];




    public function roles()
    {
        return $this->morphMany(DeviceAuthAndHierarchyElementRole::class, 'roleable');
    }

    public function responsibleRoles()
    {
        return $this->morphMany(DeviceAuthAndHierarchyElementRole::class, 'roleable')->where('role_type', DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE);
    }

    public function directSubordinateRoles()
    {
        return $this->morphMany(DeviceAuthAndHierarchyElementRole::class, 'roleable')->where('role_type', DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE);
    }

    public function customerCompany()
    {
        return $this->belongsTo(CustomerCompany::class, 'customer_company_id');
    }

    public function organizationElements(): HasMany
    {
        return $this->hasMany(OrganizationElement::class, 'type_id');
    }

    public function scopeOnlyAuthorized($q)
    {
        $user = Auth::user('sanctum');

        switch ($user->type) {
            case User::SYSTEM_ADMIN:
                return $q;
                break;

            case User::CUSTOMER_COMPANY_ADMIN:
                return $q->where('customer_company_id', $user->customerCompanyAdmin->customer_company_id);
                break;
            default:
                # code...
                break;
        }
    }

    public function scopeStatusIn($q)
    {
        $status = request('status');
        if (!$status) return $q;

        return $q->whereIn('status', explode(",", $status));
    }
}
