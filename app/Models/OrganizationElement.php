<?php

namespace App\Models;

use App\Builders\OrganizationElementBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationElement extends Model
{
    use HasFactory;

    const PREFIX = "OE";

    const STATUS_ACTIVE = "Active";
    const STATUS_INACTIVE = "Inactive";

    protected $fillable = ['name', 'prefix_id', 'type_id', 'customer_company_id', 'status'];

    public static function boot()
    {
        parent::boot();
        static::creating(function (OrganizationElement $organizationElement) {
            $organizationElement->prefix_id = getNextPrefixId('OrganizationElement', fn ($q) => $q->where('customer_company_id', $organizationElement->customer_company_id));
        });
    }

    /**
     * Customer company model builder
     *
     * @param [type] $query
     * @return CustomerCompanyBuilder
     */
    public function newEloquentBuilder($query): Builder
    {
        return new OrganizationElementBuilder($query);
    }

    public function hierarchyType()
    {
        return $this->belongsTo(HierarchyElement::class, 'type_id');
    }

    public function internalUsers(): HasMany
    {
        return $this->hasMany(OrganizationElementUser::class, 'organization_element_id');
    }

    public function directSubordinateRoleUsers(): HasMany
    {
        return $this->hasMany(OrganizationElementUser::class, 'organization_element_id')->where('type', DeviceAuthAndHierarchyElementRole::DIRECT_SUBORDINATE_ROLE);
    }

    public function responsibleRoleUsers(): HasMany
    {
        return $this->hasMany(OrganizationElementUser::class, 'organization_element_id')->where('type', DeviceAuthAndHierarchyElementRole::RESPONSIBLE_ROLE);
    }

    public function parentOrganizationElements(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationElement::class, 'organization_element_parents', 'organization_element_id', 'parent_organization_element_id')->withTimestamps();
    }
}
