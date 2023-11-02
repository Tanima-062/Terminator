<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Builders\CustomerCompanyAdminBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerCompanyAdmin extends Model
{
    use HasFactory;

    /**
     * User PREFIX constant
     */
    const CUSTOMER_COMPANY_ADMIN_PREFIX = 'CA';

    protected $fillable = [
        'user_id', 'customer_company_id', 'prefix_id', 'phone_number', 'phone_iso_code', 'full_phone_number'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->prefix_id =  self::getPrefixIdForCompany();
        });
    }


    public static function getPrefixIdForCompany()
    {
        $last_id = 1;
        $prefix = self::CUSTOMER_COMPANY_ADMIN_PREFIX;
        if ($last_supplier = self::orderBy('prefix_id', 'DESC')->first()) {
            sscanf($last_supplier->prefix_id, $prefix . "%05d", $num);
            if ($num) {
                $last_id = $num + 1;
            }
        }

        return sprintf("%s%05d", $prefix, $last_id);
    }

    /**
     * Customer company admin query builder
     *
     * @param [type] $query
     * @return CustomerCompanyAdminBuilder
     */
    public function newEloquentBuilder($query): CustomerCompanyAdminBuilder
    {
        return new CustomerCompanyAdminBuilder($query);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function customerCompany(): BelongsTo
    {
        return $this->belongsTo(CustomerCompany::class);
    }
}
