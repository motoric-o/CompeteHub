<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringCriterion extends Model
{
    protected $fillable = [
        'round_id',
        'name',
        'description',
        'max_score',
        'weight'
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function criterionScores()
    {
        return $this->hasMany(CriterionScore::class, 'criterion_id');
    }
}
