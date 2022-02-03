<?php

namespace App\Helpers;

use App\Models\Result;
use App\Models\Application;
use Carbon\Carbon;

class Stats
{
    public static function get()
    {
        return new static;
    }

    public function totalApps()
    {
        return Application::all()->count();
    }

    public function totalTestsInTwentyFourHours()
    {
        return Result::where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function totalResults($branch = 'development')
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function totalFailures($branch = 'development')
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('assertions_failed', '>=', 1)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }
}