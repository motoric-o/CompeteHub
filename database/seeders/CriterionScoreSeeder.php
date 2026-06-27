<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriterionScoreSeeder extends Seeder
{
    public function run(): void
    {
        $hackathonSemiFinal = DB::table('rounds')->where('name', 'Semi Final')->value('id');
        $hackathonGrandFinal = DB::table('rounds')->where('name', 'Grand Final')->value('id');
        $dsRound = DB::table('rounds')->where('name', 'Kaggle Phase')->value('id');
        $iotRound = DB::table('rounds')->where('name', 'Proposal Submission')->value('id');
        $mobileRound = DB::table('rounds')->where('name', 'App Prototype')->value('id');

        // Criteria
        $critInnovation = DB::table('scoring_criteria')->where('round_id', $hackathonSemiFinal)->where('name', 'Innovation & Creativity')->value('id');
        $critTech       = DB::table('scoring_criteria')->where('round_id', $hackathonSemiFinal)->where('name', 'Technical Implementation')->value('id');
        $critBiz        = DB::table('scoring_criteria')->where('round_id', $hackathonSemiFinal)->where('name', 'Business Potential')->value('id');

        $critUX         = DB::table('scoring_criteria')->where('round_id', $hackathonGrandFinal)->where('name', 'User Experience (UX)')->value('id');
        $critUI         = DB::table('scoring_criteria')->where('round_id', $hackathonGrandFinal)->where('name', 'User Interface (UI)')->value('id');

        $critDSAcc      = DB::table('scoring_criteria')->where('round_id', $dsRound)->where('name', 'Model Accuracy')->value('id');
        $critDSMeth     = DB::table('scoring_criteria')->where('round_id', $dsRound)->where('name', 'Methodology')->value('id');
        $critDSRep      = DB::table('scoring_criteria')->where('round_id', $dsRound)->where('name', 'Report & Visuals')->value('id');

        $critIoTHard    = DB::table('scoring_criteria')->where('round_id', $iotRound)->where('name', 'Hardware Reliability')->value('id');
        $critIoTVal     = DB::table('scoring_criteria')->where('round_id', $iotRound)->where('name', 'Use Case & Value')->value('id');
        $critIoTPres    = DB::table('scoring_criteria')->where('round_id', $iotRound)->where('name', 'Presentation')->value('id');

        $critMobUI      = DB::table('scoring_criteria')->where('round_id', $mobileRound)->where('name', 'User Interface & UX')->value('id');
        $critMobTech    = DB::table('scoring_criteria')->where('round_id', $mobileRound)->where('name', 'Technical Execution')->value('id');
        $critMobBiz     = DB::table('scoring_criteria')->where('round_id', $mobileRound)->where('name', 'Business Viability')->value('id');

        // Submissions
        $subAlpha   = DB::table('submissions')->where('file_path', 'submissions/alpha_penyisihan.zip')->value('id');
        $subBeta    = DB::table('submissions')->where('file_path', 'submissions/beta_penyisihan.zip')->value('id');
        $subGamma   = DB::table('submissions')->where('file_path', 'submissions/gamma_penyisihan.zip')->value('id');
        $subCanvas  = DB::table('submissions')->where('file_path', 'submissions/canvas_uiux.zip')->value('id');
        $subDian    = DB::table('submissions')->where('file_path', 'submissions/dian_ds_model.ipynb')->value('id');
        $subEko     = DB::table('submissions')->where('file_path', 'submissions/eko_ds_model.ipynb')->value('id');
        $subSmart   = DB::table('submissions')->where('file_path', 'submissions/smarthome_iot.zip')->value('id');
        $subGreen   = DB::table('submissions')->where('file_path', 'submissions/greentech_iot.zip')->value('id');
        $subWeaver  = DB::table('submissions')->where('file_path', 'submissions/techweaver_mobile.zip')->value('id');

        // Judges
        $jeryko = DB::table('users')->where('email', 'jeryko@competehub.com')->value('id');
        $rico   = DB::table('users')->where('email', 'rico@competehub.com')->value('id');
        $hassan = DB::table('users')->where('email', 'hassan@competehub.com')->value('id');
        $lina   = DB::table('users')->where('email', 'lina@competehub.com')->value('id');

        $criterionScores = [];

        // Hackathon Alpha
        $sJ_Alpha = DB::table('scores')->where('submission_id', $subAlpha)->where('user_id', $jeryko)->value('id');
        $sR_Alpha = DB::table('scores')->where('submission_id', $subAlpha)->where('user_id', $rico)->value('id');
        if ($sJ_Alpha && $critInnovation) {
            $criterionScores[] = ['score_id' => $sJ_Alpha, 'criterion_id' => $critInnovation, 'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sJ_Alpha, 'criterion_id' => $critTech,       'value' => 85.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sJ_Alpha, 'criterion_id' => $critBiz,        'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sR_Alpha && $critInnovation) {
            $criterionScores[] = ['score_id' => $sR_Alpha, 'criterion_id' => $critInnovation, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Alpha, 'criterion_id' => $critTech,       'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Alpha, 'criterion_id' => $critBiz,        'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // Hackathon Beta
        $sJ_Beta = DB::table('scores')->where('submission_id', $subBeta)->where('user_id', $jeryko)->value('id');
        $sR_Beta = DB::table('scores')->where('submission_id', $subBeta)->where('user_id', $rico)->value('id');
        if ($sJ_Beta && $critInnovation) {
            $criterionScores[] = ['score_id' => $sJ_Beta, 'criterion_id' => $critInnovation, 'value' => 75.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sJ_Beta, 'criterion_id' => $critTech,       'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sJ_Beta, 'criterion_id' => $critBiz,        'value' => 85.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sR_Beta && $critInnovation) {
            $criterionScores[] = ['score_id' => $sR_Beta, 'criterion_id' => $critInnovation, 'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Beta, 'criterion_id' => $critTech,       'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Beta, 'criterion_id' => $critBiz,        'value' => 86.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // Hackathon Gamma
        $sJ_Gamma = DB::table('scores')->where('submission_id', $subGamma)->where('user_id', $jeryko)->value('id');
        $sR_Gamma = DB::table('scores')->where('submission_id', $subGamma)->where('user_id', $rico)->value('id');
        if ($sJ_Gamma && $critInnovation) {
            $criterionScores[] = ['score_id' => $sJ_Gamma, 'criterion_id' => $critInnovation, 'value' => 75.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sJ_Gamma, 'criterion_id' => $critTech,       'value' => 78.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sJ_Gamma, 'criterion_id' => $critBiz,        'value' => 81.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sR_Gamma && $critInnovation) {
            $criterionScores[] = ['score_id' => $sR_Gamma, 'criterion_id' => $critInnovation, 'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Gamma, 'criterion_id' => $critTech,       'value' => 80.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Gamma, 'criterion_id' => $critBiz,        'value' => 83.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // Canvas UI/UX
        $sR_Canvas = DB::table('scores')->where('submission_id', $subCanvas)->where('user_id', $rico)->value('id');
        $sL_Canvas = DB::table('scores')->where('submission_id', $subCanvas)->where('user_id', $lina)->value('id');
        if ($sR_Canvas && $critUX) {
            $criterionScores[] = ['score_id' => $sR_Canvas, 'criterion_id' => $critUX, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Canvas, 'criterion_id' => $critUI, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sL_Canvas && $critUX) {
            $criterionScores[] = ['score_id' => $sL_Canvas, 'criterion_id' => $critUX, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Canvas, 'criterion_id' => $critUI, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // Dian DS
        $sH_Dian = DB::table('scores')->where('submission_id', $subDian)->where('user_id', $hassan)->value('id');
        $sL_Dian = DB::table('scores')->where('submission_id', $subDian)->where('user_id', $lina)->value('id');
        if ($sH_Dian && $critDSAcc) {
            $criterionScores[] = ['score_id' => $sH_Dian, 'criterion_id' => $critDSAcc,  'value' => 95.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Dian, 'criterion_id' => $critDSMeth, 'value' => 95.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Dian, 'criterion_id' => $critDSRep,  'value' => 95.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sL_Dian && $critDSAcc) {
            $criterionScores[] = ['score_id' => $sL_Dian, 'criterion_id' => $critDSAcc,  'value' => 93.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Dian, 'criterion_id' => $critDSMeth, 'value' => 93.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Dian, 'criterion_id' => $critDSRep,  'value' => 93.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // Eko DS
        $sH_Eko = DB::table('scores')->where('submission_id', $subEko)->where('user_id', $hassan)->value('id');
        $sL_Eko = DB::table('scores')->where('submission_id', $subEko)->where('user_id', $lina)->value('id');
        if ($sH_Eko && $critDSAcc) {
            $criterionScores[] = ['score_id' => $sH_Eko, 'criterion_id' => $critDSAcc,  'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Eko, 'criterion_id' => $critDSMeth, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Eko, 'criterion_id' => $critDSRep,  'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sL_Eko && $critDSAcc) {
            $criterionScores[] = ['score_id' => $sL_Eko, 'criterion_id' => $critDSAcc,  'value' => 89.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Eko, 'criterion_id' => $critDSMeth, 'value' => 89.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Eko, 'criterion_id' => $critDSRep,  'value' => 89.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // SmartHome IoT
        $sR_Smart = DB::table('scores')->where('submission_id', $subSmart)->where('user_id', $rico)->value('id');
        $sH_Smart = DB::table('scores')->where('submission_id', $subSmart)->where('user_id', $hassan)->value('id');
        $sL_Smart = DB::table('scores')->where('submission_id', $subSmart)->where('user_id', $lina)->value('id');
        if ($sR_Smart && $critIoTHard) {
            $criterionScores[] = ['score_id' => $sR_Smart, 'criterion_id' => $critIoTHard, 'value' => 95.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Smart, 'criterion_id' => $critIoTVal,  'value' => 95.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Smart, 'criterion_id' => $critIoTPres, 'value' => 95.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sH_Smart && $critIoTHard) {
            $criterionScores[] = ['score_id' => $sH_Smart, 'criterion_id' => $critIoTHard, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Smart, 'criterion_id' => $critIoTVal,  'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Smart, 'criterion_id' => $critIoTPres, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sL_Smart && $critIoTHard) {
            $criterionScores[] = ['score_id' => $sL_Smart, 'criterion_id' => $critIoTHard, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Smart, 'criterion_id' => $critIoTVal,  'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Smart, 'criterion_id' => $critIoTPres, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // GreenTech IoT
        $sR_Green = DB::table('scores')->where('submission_id', $subGreen)->where('user_id', $rico)->value('id');
        $sH_Green = DB::table('scores')->where('submission_id', $subGreen)->where('user_id', $hassan)->value('id');
        $sL_Green = DB::table('scores')->where('submission_id', $subGreen)->where('user_id', $lina)->value('id');
        if ($sR_Green && $critIoTHard) {
            $criterionScores[] = ['score_id' => $sR_Green, 'criterion_id' => $critIoTHard, 'value' => 85.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Green, 'criterion_id' => $critIoTVal,  'value' => 85.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Green, 'criterion_id' => $critIoTPres, 'value' => 85.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sH_Green && $critIoTHard) {
            $criterionScores[] = ['score_id' => $sH_Green, 'criterion_id' => $critIoTHard, 'value' => 87.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Green, 'criterion_id' => $critIoTVal,  'value' => 87.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Green, 'criterion_id' => $critIoTPres, 'value' => 87.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sL_Green && $critIoTHard) {
            $criterionScores[] = ['score_id' => $sL_Green, 'criterion_id' => $critIoTHard, 'value' => 86.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Green, 'criterion_id' => $critIoTVal,  'value' => 86.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Green, 'criterion_id' => $critIoTPres, 'value' => 86.00, 'created_at' => now(), 'updated_at' => now()];
        }

        // TechWeaver Mobile
        $sR_Weaver = DB::table('scores')->where('submission_id', $subWeaver)->where('user_id', $rico)->value('id');
        $sH_Weaver = DB::table('scores')->where('submission_id', $subWeaver)->where('user_id', $hassan)->value('id');
        $sL_Weaver = DB::table('scores')->where('submission_id', $subWeaver)->where('user_id', $lina)->value('id');
        if ($sR_Weaver && $critMobUI) {
            $criterionScores[] = ['score_id' => $sR_Weaver, 'criterion_id' => $critMobUI,   'value' => 88.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Weaver, 'criterion_id' => $critMobTech, 'value' => 88.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sR_Weaver, 'criterion_id' => $critMobBiz,  'value' => 88.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sH_Weaver && $critMobUI) {
            $criterionScores[] = ['score_id' => $sH_Weaver, 'criterion_id' => $critMobUI,   'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Weaver, 'criterion_id' => $critMobTech, 'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sH_Weaver, 'criterion_id' => $critMobBiz,  'value' => 92.00, 'created_at' => now(), 'updated_at' => now()];
        }
        if ($sL_Weaver && $critMobUI) {
            $criterionScores[] = ['score_id' => $sL_Weaver, 'criterion_id' => $critMobUI,   'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Weaver, 'criterion_id' => $critMobTech, 'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
            $criterionScores[] = ['score_id' => $sL_Weaver, 'criterion_id' => $critMobBiz,  'value' => 90.00, 'created_at' => now(), 'updated_at' => now()];
        }

        if (!empty($criterionScores)) {
            DB::table('criterion_scores')->insert($criterionScores);
        }
    }
}
