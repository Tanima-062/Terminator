<?php

namespace App\Models;

use App\Builders\CustomerCompanyBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use libphonenumber\PhoneNumberUtil;
use Propaganistas\LaravelPhone\PhoneNumber;

class CustomerCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix_id', 'name', 'alias_name', 'street_name', 'street_number', 'zip_code', 'city', 'country_iso_code', 'contact_person_first_name', 'contact_person_last_name', 'contact_person_email', 'contact_person_phone_iso_code', 'contact_person_phone', 'contact_person_full_phone_number', 'auto_logout_time', 'device_authentication_required', 'hierarchy_elements_required', 'status'
    ];

    const PREFIX = 'CC';
    /**
     * Status constants
     */
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    protected $casts = [
        'device_authentication_required'        =>  'boolean',
        'hierarchy_elements_required'      =>  'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (CustomerCompany $customerCompany) {
            $customerCompany->prefix_id = getNextPrefixId('CustomerCompany');

            if ($customerCompany->contact_person_phone_iso_code && $customerCompany->contact_person_phone) {
                try {
                    $customerCompany->contact_person_full_phone_number =  PhoneNumber::make($customerCompany->contact_person_phone, strtoupper($customerCompany->contact_person_phone_iso_code))->formatE164();
                } catch (\Throwable $th) {
                    $customerCompany->contact_person_full_phone_number = "+" . getCountryCodeByAbbreviation($customerCompany->contact_person_phone_iso_code) . "$customerCompany->contact_person_phone";
                }
            }
        });

        static::updating(function (CustomerCompany $customerCompany) {
            if ($customerCompany->contact_person_phone_iso_code && $customerCompany->contact_person_phone) {
                try {
                    $customerCompany->contact_person_full_phone_number =  PhoneNumber::make($customerCompany->contact_person_phone, $customerCompany->contact_person_phone_iso_code)->formatE164();
                } catch (\Throwable $th) {
                    $customerCompany->contact_person_full_phone_number = "+" . getCountryCodeByAbbreviation($customerCompany->contact_person_phone_iso_code) . "$customerCompany->contact_person_phone";
                }
            }
        });
    }


    /**
     * Customer company model builder
     *
     * @param [type] $query
     * @return CustomerCompanyBuilder
     */
    public function newEloquentBuilder($query): CustomerCompanyBuilder
    {
        return new CustomerCompanyBuilder($query);
    }

    public function deviceAuthRoles()
    {
        return $this->morphMany(DeviceAuthAndHierarchyElementRole::class, 'roleable');
    }

    public function hierarchyElements()
    {
        return $this->hasMany(HierarchyElement::class, 'customer_company_id');
    }

    public function getContactPersonNameAttribute()
    {
        return $this->contact_person_first_name . ' ' . $this->contact_person_last_name;
    }

    public function customerCompanyAdmins()
    {
        return $this->hasMany(CustomerCompanyAdmin::class, 'customer_company_id');
    }

    public function allocations()
    {
        return $this->hasMany(ContactDataRecordAllocate::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

}
