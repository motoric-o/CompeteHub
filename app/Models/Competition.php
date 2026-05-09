<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    public $incrementing = true;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'description',
        'type',
        'scoring_type_id',
        'registration_fee',
        'quota',
        'banner_url',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'status',
        'rules',
        'settings'
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function scoringType()
    {
        return $this->belongsTo(ScoringType::class);
    }

    public function scoringCriteria()
    {
        return $this->hasMany(ScoringCriterion::class);
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function juryAssignments()
    {
        return $this->hasMany(JuryAssignment::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
