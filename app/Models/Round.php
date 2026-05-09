<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'competition_id',
        'name',
        'round_order',
        'start_date',
        'end_date',
        'status'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function brackets()
    {
        return $this->hasMany(Bracket::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
