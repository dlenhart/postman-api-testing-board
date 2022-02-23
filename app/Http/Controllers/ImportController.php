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

    public function __construct(ApplicationRepository $appRepository)
    {
        $this->appRepository = $appRepository;
        $this->service = new ImportService($this->appRepository);
    }

    public function import(Request $request)
    {
        return $this->service->import($request);
    }
}
