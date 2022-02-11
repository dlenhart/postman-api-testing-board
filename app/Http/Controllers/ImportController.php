<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;
use App\Repositories\ApplicationRepository;


class ImportController extends Controller
{
    private $service;
    private $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
        $this->service = new ImportService($this->repository);
    }

    public function import(Request $request)
    {
        return $this->service->import($request);
    }
}
