<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Builders\UserBuilder;
use App\Traits\HasPermission;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermission;

    /**
     * User Type constant
     */
    const SYSTEM_ADMIN = 'system_admin';
    const CUSTOMER_COMPANY_ADMIN = 'company_admin';
    const INTERNAL_USER = 'internal_user';
    const BROKER_USER = 'broker_user';


    /**
     * User Type full name
     */
    const USER_TYPE = [
        'system_admin' => 'System Admin',
        'company_admin' => 'Customer Company Admin',
        'internal_user' => 'Internal User',
        'broker_user' => 'Broker User',
    ];


    /**
     * User statys constant
     */
    const NEW = 'new';
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const EMAIL_VERIFICATION_PENDING = 'email_verification_pending';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language_id',
        'photo',
        'first_name',
        'last_name',
        'full_name',
        'send_mail',
        'email',
        'password',
        'email_verified_at',
        'verification_token',
        'type',
        'status',
        'customer_company_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (User $user) {
            $user->full_name = "$user->first_name $user->last_name";
        });
        static::updating(function (User $user) {
            $user->full_name = "$user->first_name $user->last_name";
        });
    }

    /**
     * User Model builder
     *
     * @param [type] $query
     * @return UserBuilder
     */
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }



    public function getPhotoUrlAttribute()
    {
        if (is_null($this->photo)) {
            // return asset("images/photo.png");
            return null;
        }

        if (preg_match("@http@", $this->photo)) {
            return $this->photo;
        }

        return Storage::disk(env('FILESYSTEM_DISK'))->url($this->photo);
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(CustomerCompany::class,'customer_company_id');
    }

    public function resetToken()
    {
        $column_name = "email";

        return $this->hasOne(PasswordReset::class, $column_name, $column_name);
    }

    public function customerCompany()
    {
        return $this->belongsTo(CustomerCompany::class, 'customer_company_id');
    }

    public function customerCompanyAdmin()
    {
        return $this->hasOne(CustomerCompanyAdmin::class);
    }


    public function internalUser()
    {
        return $this->hasOne(InternalUser::class, 'user_id', 'id');
    }

    public function brokerUser()
    {
        return $this->hasOne(BrokerUser::class, 'user_id', 'id');
    }

    public function organizationElementUser()
    {
        return $this->hasmany(OrganizationElementUser::class, 'user_id', 'id');
    }

    public function customOrganizationElementUser()
    {
        return $this->hasmany(CustomOrganizationElementUser::class, 'user_id', 'id');
    }

    public function campaignInternal()
    {
        return $this->hasmany(CampaignInternal::class, 'user_id', 'id');
    }

    public function alignmentUser()
    {
        return $this->hasmany(AlignmentUser::class, 'user_id', 'id');
    }
    public function competence()
    {
        return $this->hasmany(Competence::class, 'user_id', 'id');
    }

}
