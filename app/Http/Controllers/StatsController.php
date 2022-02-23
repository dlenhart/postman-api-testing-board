<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants;
use App\Repositories\ResultsRepository;
use App\Repositories\ApplicationRepository;
use App\Helpers\Stats;


class StatsController extends Controller
{
    public function __construct(
        ResultsRepository $results,
        ApplicationRepository $applications
    ) {
        $this->resultsRepository = $results;
        $this->appRepository = $applications;
    }

    public function countAppStats(Request $request)
    {
        $app_id = $this->appRepository
            ->getApplicationByName($request->app);

        if (!$app_id) {
            return response()->json([
                'message' => Constants::APP_NOT_FOUND,
                'status' => 'error',
            ], 404);
        }

        $total = $this->resultsRepository
            ->countAppResults($app_id->id);

        $failures = $this->resultsRepository
            ->countAppFailures($app_id->id);

        $data = [
            'total'     => $total,
            'failures'  => $failures,
            'percent'   => $this->percent($failures, $total)
        ];

        return response()->json($data);
    }

    public function countOverallStats()
    {
        $fails_dev = $this->failures(
            Stats::get()->totalFailures('development'),
            'development'
        );

        $fails_prod = $this->failures(
            Stats::get()->totalFailures('master'),
            'master'
        );

        $data = [
            'total_apps_tested'    =>      Stats::get()->totalApps(),                      
            'twenty_four_hours'    =>      Stats::get()->totalTestsInTwentyFourHours(),
            'development'          =>      $fails_dev,
            'master'               =>      $fails_prod
        ];

        return response()->json($data);
    }

    public function percent(int $failures, int $total): float
    {
        return round(100 - (($failures / $total) * 100), 2);
    }

    public function failures(int $failures, string $branch): float
    {
        return round(100 -
            (($failures == 0 ? 0 : $failures / Stats::get()->totalResults($branch)) * 100), 2);
    }
}
