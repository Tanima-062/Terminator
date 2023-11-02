<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetenceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'competence_id', 'created_by', 'status'
    ];

    public function competence()
    {

        return $this->belongsTo(Competence::class, "competence_id");
    }
    
    public function user()
    {

        return $this->belongsTo(User::class, "created_by");
    }

}
