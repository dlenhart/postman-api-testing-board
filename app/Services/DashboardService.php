<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\ResultsRepository;
use App\Repositories\ApplicationRepository;
use Illuminate\Support\Facades\Validator;
use App\Constants;


class DashboardService
{
    public function __construct(
        ResultsRepository $resultsRepository,
        ApplicationRepository $applications
    ) {
        $this->resultsRepository = $resultsRepository;
        $this->appRepository = $applications;
    }

    public function results()
    {
        return $this->resultsRepository
            ->selectAllwithApplication()
            ->paginate(25);
    }

    public function result(Request $request)
    {
        $result = $this->resultsRepository
            ->getFirstResultById($request->id);
        $result->makeHidden(['raw_data']);

        return response()->json($result, 200);
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => Constants::VALIDATION_ERRORS,
                'status' => Constants::ERROR,
            ], 403);
        }

        $record = $this->resultsRepository
            ->getFirstResultByIdOrFail($request->id);
        $record->delete();

        return response()->json(200);
    }

    public function listApps()
    {
        $apps = $this->appRepository->getAllApplications();
        return response()->json($apps, 200);
    }

    public function application(Request $request)
    {
        $app_id = $this->appRepository
            ->getApplicationByName($request->app);

        if (!$app_id) {
            return response()->json([
                'message' => Constants::APP_NOT_FOUND,
                'status' => Constants::ERROR,
            ], 404);
        }

        $app = $this->resultsRepository
            ->selectAllByApplicationId($app_id->id)
            ->paginate(25);

        if ($app) {
            return response()->json($app, 200);
        }
    }
}
