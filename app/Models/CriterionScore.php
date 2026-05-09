<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterionScore extends Model
{
    protected $fillable = [
        'score_id',
        'criterion_id',
        'value'
    ];

    public function score()
    {
        return $this->belongsTo(Score::class, 'score_id');
    }

    public function criterion()
    {
        return $this->belongsTo(ScoringCriterion::class, 'criterion_id');
    }
}
