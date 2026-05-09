<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JuryAssignment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'competition_id',
        'assigned_at'
    ];

    public function user() // jury
    {
        return $this->belongsTo(User::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
