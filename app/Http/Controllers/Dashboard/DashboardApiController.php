<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ResultsRepository;
use App\Repositories\ApplicationRepository;
use App\Services\DashboardService;

class DashboardApiController extends Controller
{
    private $resultsRepository;
    private $appRepository;
    private $dashService;

    public function __construct(
        ResultsRepository $results,
        ApplicationRepository $applications
    ) {
        $this->resultsRepository = $results;
        $this->appRepository = $applications;

        $this->dashService = new DashboardService(
            $this->resultsRepository,
            $this->appRepository
        );
    }

    public function results()
    {        
        return $this->dashService->results();
    }

    public function result(Request $request)
    {
        return $this->dashService->result($request);
    }

    public function delete(Request $request)
    {
        return $this->dashService->delete($request);
    }

    public function listApps()
    {
        return $this->dashService->listApps();
    }

    public function application(Request $request)
    {
        return $this->dashService->application($request);
    }
}
