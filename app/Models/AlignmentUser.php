<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlignmentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'alignment_id',
    ];

    public function  user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // alignment_id 1 for Lead
    // alignment_id 2 for Terminierung
}
