<?php

namespace App\Helpers;

use App\Models\Result;
use App\Repositories\ResultsRepository;
use App\Repositories\ApplicationRepository;
use App\Models\Application;
use Carbon\Carbon;

class Stats
{
    public function __construct()
    {
        $this->resultsRepository = new ResultsRepository;
    }

    public static function get(): static
    {
        return new static;
    }

    public function totalApps(): int
    {
        return Application::all()->count();
    }

    public function totalTestsInTwentyFourHours(): int
    {
        return Result::where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function totalResults(string $branch = 'development'): int
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function totalFailures(string $branch = 'development'): int
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('assertions_failed', '>=', 1)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
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
