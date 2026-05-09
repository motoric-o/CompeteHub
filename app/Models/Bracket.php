<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bracket extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'round_id',
        'participant_a',
        'participant_b',
        'participant_type',
        'winner_id',
        'scheduled_at',
        'created_at'
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }
}
