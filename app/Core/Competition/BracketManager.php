<?php

namespace App\Core\Competition;

use App\Models\Round;
use App\Models\Bracket;
use App\Models\Registration;
use Illuminate\Support\Collection;

class BracketManager
{
    public function autoGenerate(Round $round): void
    {
        $competition = $round->competition;
        
        $registrations = Registration::where('competition_id', $competition->id)
            ->where('status', 'payment_ok')
            ->get();

        if ($registrations->isEmpty()) {
            return;
        }

        $shuffledRegistrations = $registrations->shuffle();

        $participantType = $competition->type; 
        $brackets = [];

        $participantsCount = $shuffledRegistrations->count();
        
        for ($i = 0; $i < $participantsCount; $i += 2) {
            $participantA = $shuffledRegistrations[$i];
            $participantB = ($i + 1 < $participantsCount) ? $shuffledRegistrations[$i + 1] : null;

            $brackets[] = [
                'round_id' => $round->id,
                'participant_a' => $participantType === 'team' ? $participantA->team_id : $participantA->user_id,
                'participant_b' => $participantB ? ($participantType === 'team' ? $participantB->team_id : $participantB->user_id) : null,
                'participant_type' => $participantType === 'team' ? 'team' : 'user', 
                'winner_id' => $participantB ? null : ($participantType === 'team' ? $participantA->team_id : $participantA->user_id), 
                'created_at' => now(),
            ];
        }

        if (!empty($brackets)) {
            Bracket::where('round_id', $round->id)->delete();
            Bracket::insert($brackets);
        }
    }
}
