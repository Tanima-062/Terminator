<?php

namespace App\Models;

use App\Contracts\CompanyWisePrefixIDContract;
use App\Traits\CompanyWisePrefixID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDataRecordAllocate extends Model
{
    use HasFactory, CompanyWisePrefixID;


    protected $fillable = [
        'prefix_id', 'allocate_by_user_id',  'customer_company_id', 'contact_data_record_id', 'user_id', 'broker_user_id', 'internal_user_id', 'organization_element_id', 'broker_id', 'campaign_id', 'type'
    ];

    const PREFIX = '';

    const LEADER_HEAD_OF = 'Leader Head of';
    const MANAGER = 'Manager';
    const QUALITY_CONTROLLER = 'Quality controller';
    const CALL_AGENT = 'Call agent';
    const BROKER = 'Broker';
    const BROKER_USER = 'Broker user';
    const VARIABLE_A = 'variableA';


    public static function getPrefix(): string
    {
        return self::PREFIX;
    }


    public function customerCompany()
    {
        return $this->belongsTo(CustomerCompany::class);
    }

    public function contactDataRecord()
    {
        return $this->belongsTo(ContactDataRecord::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }


    public function organizationElement()
    {
        return $this->belongsTo(OrganizationElement::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function assignedBroker(): Attribute
    {
        return Attribute::get(function () {
            switch ($this->type) {
                case self::BROKER:
                    return $this->broker;
                case self::BROKER_USER;
                    return $this->user;
                default:
                    return null;
                    break;
            }
        });
    }

    public function assignedOrganizationElement(): Attribute
    {
        return Attribute::get(fn() => $this->type == self::VARIABLE_A ? $this->organizationElement->load('hierarchyType') : null);
    }
}
