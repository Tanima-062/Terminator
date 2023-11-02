<?php

namespace App\Models;

use App\Contracts\CompanyWisePrefixIDContract;
use App\Traits\CompanyWisePrefixID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Builders\BrokerUserBuilder;

class BrokerUser extends Model implements CompanyWisePrefixIDContract
{
    use HasFactory, CompanyWisePrefixID;

    protected $fillable = [
        'user_id', 'broker_id', 'customer_company_id', 'prefix_id', 'correspondence_language', 'salutation', 'phone_iso_code', 'phone', 'full_phone_number', 'role'
    ];

    const PREFIX = 'BB';
    const ADMIN = 'Admin';
    const INTERMEDIARY = 'Intermediary';


    public static function getPrefix(): string {
        return self::PREFIX;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }


    public function customerCompany()
    {
        return $this->belongsTo(CustomerCompany::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (BrokerUser $brokerUser) {

            $brokerUser->prefix_id = self::generatePrefixId($brokerUser);
            if($brokerUser->phone){
                $brokerUser->full_phone_number = "+".getCountryCodeByAbbreviation($brokerUser->phone_iso_code).$brokerUser->phone;
            }
        });

        static::updating(function (BrokerUser $brokerUser) {

            if($brokerUser->phone){
                $brokerUser->full_phone_number = "+".getCountryCodeByAbbreviation($brokerUser->phone_iso_code).$brokerUser->phone;
            }
        });
    }

    public function newEloquentBuilder($query): BrokerUserBuilder
    {
        return new BrokerUserBuilder($query);
    }


}
