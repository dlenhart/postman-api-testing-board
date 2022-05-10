<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use App\Repositories\ResultsRepository;
use App\Helpers\Stats;
use App\Constants;
use Illuminate\Http\JsonResponse;

class StatsService
{
    public function __construct(
        ApplicationRepository $applicationRepository,
        ResultsRepository $resultsRepository
    ) {
        $this->appRepository = $applicationRepository;
        $this->resultsRepository = $resultsRepository;
    }

    public function countApplicationStats($request): JsonResponse
    {
        $app_id = $this->appRepository
            ->getApplicationByName($request->app);

        if (!$app_id) {
            return response()->json([
                'message' => Constants::APP_NOT_FOUND,
                'status' => Constants::ERROR,
            ], 404);
        }

        $total = $this->resultsRepository
            ->countAppResults($app_id->id);

        $failures = $this->resultsRepository
            ->countAppFailures($app_id->id);

        $data = [
            'total'     => $total,
            'failures'  => $failures,
            'percent'   => Stats::percent($failures, $total)
        ];

        return response()->json($data);
    }

    public function countOverallStatistics(): JsonResponse
    {
        $fails_dev = Stats::failures(
            Stats::get()->totalFailures('development'),
            'development'
        );

        $fails_prod = Stats::failures(
            Stats::get()->totalFailures('master'),
            'master'
        );

        $data = [
            'total_apps_tested'    =>    Stats::get()->totalApps(),
            'twenty_four_hours'    =>    Stats::get()->totalTestsInTwentyFourHours(),
            'development'          =>    $fails_dev,
            'master'               =>    $fails_prod
        ];

        return response()->json($data);
    }
}
