<?php

namespace App\Helpers;

use App\Repositories\ResultsRepository;
use App\Repositories\ApplicationRepository;


class Stats
{
    public function __construct()
    {
        $this->resultsRepository = new ResultsRepository;
        $this->appRepository = new ApplicationRepository;
    }

    public static function get(): static
    {
        return new static;
    }

    public function totalApps(): int
    {
        return $this->appRepository->getTotalApplications();
    }

    public function totalTestsInTwentyFourHours(): int
    {
        return $this->resultsRepository
            ->countTotalTestsInTwentyFourHours();
    }

    public function totalResults(string $branch = 'development'): int
    {
        return $this->resultsRepository
            ->countTotalResultsByBranch($branch);
    }

    public function totalFailures(string $branch = 'development'): int
    {
        return $this->resultsRepository
            ->countTotalFailures($branch);
    }

    public static function percent(int $failures, int $total): float
    {
        return round(100 - (($failures / $total) * 100), 2);
    }

    public static function failures(int $failures, string $branch): float
    {
        return round(100 -
            (($failures == 0 ? 0 : $failures / Stats::get()->totalResults($branch)) * 100), 2);
    }
}
