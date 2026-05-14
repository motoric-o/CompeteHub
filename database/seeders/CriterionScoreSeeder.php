<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriterionScoreSeeder extends Seeder
{
    public function run(): void
    {
        $hackathonId = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        
        $critInnovation = DB::table('scoring_criteria')->where('competition_id', $hackathonId)->where('name', 'Innovation & Creativity')->value('id');
        $critTech = DB::table('scoring_criteria')->where('competition_id', $hackathonId)->where('name', 'Technical Implementation')->value('id');
        $critBiz = DB::table('scoring_criteria')->where('competition_id', $hackathonId)->where('name', 'Business Potential')->value('id');

        $submAlpha = DB::table('submissions')->where('file_path', 'submissions/alpha_penyisihan.zip')->value('id');
        $jeryko = DB::table('users')->where('email', 'jeryko@competehub.com')->value('id');
        $rico   = DB::table('users')->where('email', 'rico@competehub.com')->value('id');

        $scoreJeryko = DB::table('scores')->where('submission_id', $submAlpha)->where('user_id', $jeryko)->value('id');
        $scoreRico = DB::table('scores')->where('submission_id', $submAlpha)->where('user_id', $rico)->value('id');

        $criterionScores = [];

        if ($scoreJeryko && $critInnovation && $critTech && $critBiz) {
            $criterionScores[] = ['score_id' => $scoreJeryko, 'criterion_id' => $critInnovation, 'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $scoreJeryko, 'criterion_id' => $critTech, 'value' => 85.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $scoreJeryko, 'criterion_id' => $critBiz, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }

        if ($scoreRico && $critInnovation && $critTech && $critBiz) {
            $criterionScores[] = ['score_id' => $scoreRico, 'criterion_id' => $critInnovation, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $scoreRico, 'criterion_id' => $critTech, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $scoreRico, 'criterion_id' => $critBiz, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }

        if (!empty($criterionScores)) {
            DB::table('criterion_scores')->insert($criterionScores);
        }
    }
}
