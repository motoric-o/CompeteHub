<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    const CREATED_AT = 'submitted_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'competition_id',
        'round_id',
        'user_id',
        'team_id',
        'file_path',
        'file_type',
        'file_size',
        'submitted_at',
        'final_score',
        'status'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
