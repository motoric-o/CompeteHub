<?php

namespace App\Core\Scoring;

interface ScoringStrategy
{
    public function calculate($scoringObject);  //?
}
