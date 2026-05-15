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

    public function teamA() { return $this->belongsTo(Team::class, 'participant_a'); }
    public function teamB() { return $this->belongsTo(Team::class, 'participant_b'); }
    public function teamWinner() { return $this->belongsTo(Team::class, 'winner_id'); }

    public function userA() { return $this->belongsTo(User::class, 'participant_a'); }
    public function userB() { return $this->belongsTo(User::class, 'participant_b'); }
    public function userWinner() { return $this->belongsTo(User::class, 'winner_id'); }

    public function getParticipantA()
    {
        if (!$this->participant_a) return null;
        return $this->participant_type === 'team' ? $this->teamA : $this->userA;
    }

    public function getParticipantB()
    {
        if (!$this->participant_b) return null;
        return $this->participant_type === 'team' ? $this->teamB : $this->userB;
    }

    public function getWinner()
    {
        if (!$this->winner_id) return null;
        return $this->participant_type === 'team' ? $this->teamWinner : $this->userWinner;
    }

    public function getParticipantAName()
    {
        $participant = $this->getParticipantA();
        return $participant ? $participant->name : 'TBD';
    }

    public function getParticipantBName()
    {
        $participant = $this->getParticipantB();
        return $participant ? $participant->name : 'TBD';
    }

    public function getWinnerName()
    {
        $winner = $this->getWinner();
        return $winner ? $winner->name : 'TBD';
    }
}
