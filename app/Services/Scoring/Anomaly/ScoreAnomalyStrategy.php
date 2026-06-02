<?php

namespace App\Services\Scoring\Anomaly;

use Illuminate\Support\Collection;

interface ScoreAnomalyStrategy
{
    public function detect(Collection $scores): array;

    public function name(): string;
}
