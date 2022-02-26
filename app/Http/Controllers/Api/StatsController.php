<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\ResultsRepository;
use App\Repositories\ApplicationRepository;
use App\Services\StatsService;
use App\Http\Controllers\Controller;


class StatsController extends Controller
{
    private $resultsRepository;
    private $appRepository;
    private $statsService;

    public function __construct(
        ResultsRepository $results,
        ApplicationRepository $applications
    ) {
        $this->resultsRepository = $results;
        $this->appRepository = $applications;

        $this->statsService = new StatsService(
            $this->appRepository,
            $this->resultsRepository
        );
    }

    public function countAppStats(Request $request)
    {
        return $this->statsService->countApplicationStats($request);
    }

    public function countOverallStats()
    {
        return $this->statsService->countOverallStatistics();
    }
}
