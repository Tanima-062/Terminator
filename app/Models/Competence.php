<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix_id', 'user_id','type', 'lang_code','status', 'level', 'other_competence'
    ];

    /**
     * User PREFIX constant
     */
    const PREFIX = 'KP';
    public static function boot()
    {
        parent::boot();
        static::creating(function (Competence $competence) {
            $competence->prefix_id = getNextPrefixId('Competence', fn ($q) => $q->where('user_id', $competence->user_id));
        });
    }

    public function competenceLog() {

        return $this->hasMany(CompetenceLog::class, "competence_id");
    }

}
