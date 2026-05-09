<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    const CREATED_AT = 'scored_at';
    
    protected $fillable = [
        'submission_id',
        'user_id',
        'score',
        'notes',
        'scored_at',
        'updated_at'
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function user() // jury
    {
        return $this->belongsTo(User::class);
    }

    public function criterionScores()
    {
        return $this->hasMany(CriterionScore::class, 'score_id');
    }
}
