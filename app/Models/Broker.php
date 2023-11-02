<?php

namespace App\Models;

use App\Builders\BrokerBuilder;
use App\Contracts\CompanyWisePrefixIDContract;
use App\Traits\CompanyWisePrefixID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\PhoneNumber;

class Broker extends Model implements CompanyWisePrefixIDContract
{
    use HasFactory, CompanyWisePrefixID;

    protected $fillable = [
        'customer_company_id', 'prefix_id', 'name', 'street_name', 'street_number', 'zip_code', 'city', 'country_iso_code', 'contact_person_first_name', 'contact_person_last_name', 'contact_person_full_name','contact_person_email', 'contact_person_phone_iso_code', 'contact_person_phone', 'contact_person_full_phone_number', 'status'
    ];

    const PREFIX = 'BR';

    public static function getPrefix(): string {
        return self::PREFIX;
    }

    /**
     * Status constants
     */
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    public static function boot()
    {
        parent::boot();
        static::creating(function (Broker $broker) {
            $broker->prefix_id = self::generatePrefixId($broker);

            $broker->contact_person_full_name = $broker->contact_person_first_name . ' ' . $broker->contact_person_last_name;
            $broker->contact_person_full_phone_number = "+".getCountryCodeByAbbreviation($broker->contact_person_phone_iso_code).$broker->contact_person_phone;
        });

        static::updating(function (Broker $broker) {
       
            $broker->contact_person_full_name = $broker->contact_person_first_name . ' ' . $broker->contact_person_last_name;
            $broker->contact_person_full_phone_number = "+".getCountryCodeByAbbreviation($broker->contact_person_phone_iso_code).$broker->contact_person_phone;
        });
    }

    public function customerCompany()
    {
        return $this->belongsTo(CustomerCompany::class);
    }

    public function newEloquentBuilder($query): BrokerBuilder
    {
        return new BrokerBuilder($query);
    }

}
