<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Builders\InternalUserBuilder;

class InternalUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'roles_id', 'customer_company_id', 'salutation', 'correspondence_language_code', 'phone_number', 'full_phone_number', 'phone_iso_code', 'access_right'
    ];
    /**
     * User PREFIX constant
     */
    const PREFIX = 'BN';

    public static function boot()
    {
        parent::boot();
        static::creating(function (InternalUser $internalUser) {
            $internalUser->prefix_id = getNextPrefixId('InternalUser', fn ($q) => $q->where('customer_company_id', $internalUser->customer_company_id));
        });
    }


    /**
     * Internal user model builder
     *
     * @param [type] $query
     * @return InternalUserBuilder
     */
    public function newEloquentBuilder($query): InternalUserBuilder
    {
        return new InternalUserBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customerCompany(): BelongsTo
    {
        return $this->belongsTo(CustomerCompany::class, 'company_id');
    }

    public function companyRole(): BelongsTo
    {
        return $this->belongsTo(CompanyRole::class, 'roles_id');
    }
}
