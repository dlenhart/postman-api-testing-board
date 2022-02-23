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

    public static function get()
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

    public function totalResults($branch = 'development'): int
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function totalFailures($branch = 'development'): int
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('assertions_failed', '>=', 1)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }
}