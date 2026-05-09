<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringCriterion extends Model
{
    protected $fillable = [
        'competition_id',
        'name',
        'description',
        'max_score',
        'weight'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function criterionScores()
    {
        return $this->hasMany(CriterionScore::class, 'criterion_id');
    }
}
