<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix_id', 'name', 'status'
    ];

    public function scopeActive($q)
    {

        return $q->where('status', 'active');
    }

    public function campaignInternal()
    {
        
        return $this->hasMany(CampaignInternal::class,'campaign_id', 'id');

    }

}
