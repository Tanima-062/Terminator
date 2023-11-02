<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRole extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    const LEADER = 'Leader';
    const MANAGER = 'Manager';
    const QUALITY_CONTROLLER= 'Quality controller';
    const CALL_AGENT = 'Call agent';

    public function internalUser()
    {
        return $this->hasOne(InternalUser::class);
    }
}
