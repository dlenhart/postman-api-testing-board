<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;
use App\Repositories\ApplicationRepository;
use App\Repositories\ResultsRepository;


class ImportController extends Controller
{
    private $service;
    private $appRepository;
    private $resultsRepository;

    public function __construct(
        ApplicationRepository $application,
        ResultsRepository $results
    ) {
        $this->appRepository = $application;
        $this->resultsRepository = $results;

        $this->service = new ImportService(
            $this->appRepository, 
            $this->resultsRepository
        );
    }

    public function import(Request $request)
    {
        return $this->service->import($request);
    }
}
