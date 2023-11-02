<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationElementUser extends Model
{
    use HasFactory;

    protected $fillable = ['organization_element_id', 'user_id', 'type', 'created_at', 'updated_at'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function organizationElement(): BelongsTo
    {
        return $this->belongsTo(OrganizationElement::class, 'organization_element_id');
    }
}
