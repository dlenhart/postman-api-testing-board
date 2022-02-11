<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Repositories\ApplicationRepository;
use App\Models\Result;
use App\Mail\SendResults;
use App\Helpers\File;
use App\Helpers\Parser;
use App\Constants;

class ImportService
{
    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function import($request): JsonResponse
    {
        if ($request->hasFile('file')) {
            $results = File::extractRunInfo(
                $request->file('file')
            );

            if ($results['extension'] != 'json') {
                return response()->json([
                    'message' => Constants::INVALID_EXTENSION,
                    'status' => 'error',
                ], 400);
            }

            $application_id = $this->findApplicationOrCreate($results['application']);
            $file = file_get_contents($request->file('file'), "r") or die(Constants::UNABLE_TO_OPEN_FILE);
            $encode = json_decode($file, true);

            if (!isset($encode['collection'])) {
                return response()->json([
                    'message' => Constants::INVALID_FILE,
                    'status' => 'error',
                ], 400);
            }

            $resultMessages = Parser::buildResults(
                $encode['collection']['item'],
                $encode
            );

            $result = $this->insertResults(
                $application_id,
                $results['branch'],
                $resultMessages,
                $encode['run'],
                $file
            );

            if ($result) {
                if ($request->email) {
                    $this->sendResultsEmail($result, $request->email);
                }

                $data = [
                    'application'       => $results['application'],
                    'application_id'    => $application_id,
                    'send_to'           => $request->email ? $request->email : '',
                    'result_id'         => $result
                ];

                return response()->json([
                    'message' =>  Constants::IMPORT_SUCCESS,
                    'status' => 'success',
                    'data' => $data
                ], 200);
            }

            return response()->json([
                'message' => Constants::IMPORT_FAILED,
                'status' => 'error',
            ], 500);
        }

        return response()->json([
            'message' => Constants::FILE_NOT_FOUND,
            'status' => 'error',
        ], 400);
    }

    public function findApplicationOrCreate($application)
    {
        $app = $this->applicationRepository->getApplicationByName($application);

        if ($app)
            return $app->id;

        return $this->applicationRepository->create($application);
    }

    public function insertResults($application_id, $branch, $parsedResults, $stats)
    {
        try {
            $result = new Result;
            $result->application_id             = $application_id;
            $result->branch                     = $branch;
            $result->iterations_total           = $stats['stats']['iterations']['total'];
            $result->iterations_failed          = $stats['stats']['iterations']['failed'];
            $result->items_total                = $stats['stats']['items']['total'];
            $result->items_failed               = $stats['stats']['items']['failed'];
            $result->scripts_total              = $stats['stats']['scripts']['total'];
            $result->scripts_failed             = $stats['stats']['scripts']['failed'];
            $result->prerequests_total          = $stats['stats']['prerequests']['total'];
            $result->prerequests_failed         = $stats['stats']['prerequests']['failed'];
            $result->requests_total             = $stats['stats']['requests']['total'];
            $result->requests_failed            = $stats['stats']['requests']['failed'];
            $result->tests_total                = $stats['stats']['tests']['total'];
            $result->tests_failed               = $stats['stats']['tests']['failed'];
            $result->assertions_total           = $stats['stats']['assertions']['total'];
            $result->assertions_failed          = $stats['stats']['assertions']['failed'];
            $result->testScripts_total          = $stats['stats']['testScripts']['total'];
            $result->testScripts_failed         = $stats['stats']['testScripts']['failed'];
            $result->prerequestScripts_total    = $stats['stats']['prerequestScripts']['total'];
            $result->prerequestScripts_failed   = $stats['stats']['prerequestScripts']['failed'];
            $result->responseAverage            = $stats['timings']['responseAverage'];
            $result->responseMin                = $stats['timings']['responseMin'];
            $result->responseMax                = $stats['timings']['responseMax'];
            $result->started                    = $stats['timings']['started'];
            $result->completed                  = $stats['timings']['completed'];
            $result->duration                   = $stats['timings']['completed'] - $stats['timings']['started'];
            $result->parsed_results             = $parsedResults;
            $result->save();
        } catch (\Exception $e) {
            Log::error('ERROR***** inserting record, ' . $e);
        }

        if ($result)
            return $result->id;

        return false;
    }

    public function sendResultsEmail($id, $email)
    {
        $result = Result::select(
            'id',
            'application_id',
            'branch',
            'iterations_total',
            'iterations_failed',
            'requests_total',
            'requests_failed',
            'tests_total',
            'tests_failed',
            'assertions_total',
            'assertions_failed',
            'started',
            'completed',
            'duration',
            'parsed_results',
            'created_at'
        )->where('id', $id)->with('application')->first();

        $emails = [$email, 'drew_lenhart@sweetwater.com'];

        Mail::to($emails)->send(new SendResults($result));
    }
}
