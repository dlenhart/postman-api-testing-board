<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Result;
use App\Helpers\File;
use App\Helpers\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendResults;
use App\Constants;
use App\Services\ImportService;

class ImportController extends Controller
{
    private $service;

    public function __construct(ImportService $service)
    {
        $this->service = $service;
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $explode = File::extractRunInfo(
                $request->file('file')
            );

            if ($explode['extension'] != 'json') {
                return response()->json([
                    'message' => 'File extension is not json',
                    'status' => 'error',
                ], 400);
            }

            $application_id = $this->findApplicationOrCreate($explode['application']);
            $file = file_get_contents($request->file('file'), "r") or die("Unable to open file!");
            $encode = json_decode($file, true);

            if (!isset($encode['collection'])) {
                return response()->json([
                    'message' => 'File is not a valid json file',
                    'status' => 'error',
                ], 400);
            }

            $resultMessages = Parser::buildResults(
                $encode['collection']['item'], 
                $encode
            );

            $result = $this->insertResults(
                $application_id, 
                $explode['branch'], 
                $resultMessages, 
                $encode['run'], 
                $file
            );

            if ($result) {
                if ($request->email) {
                    $this->sendResultsEmail($result, $request->email);
                }

                $data = [
                    'application'       => $explode['application'],
                    'application_id'    => $application_id,
                    'send_to'           => $request->email ? $request->email : '',
                    'result_id'         => $result
                ];

                return response()->json([
                    'message' => 'File imported successfully',
                    'status' => 'success',
                    'data' => $data
                ], 200);
            }

            return response()->json([
                'message' => "Error inserting results {$explode['application']} - {$result}" ,
                'status' => 'error',
            ], 500);

        }

        return response()->json([
            'message' => 'File not found',
            'status' => 'error',
        ], 400);
    }

    public function findApplicationOrCreate($application)
    {
        $app = Application::where('name', $application)->first();

        if ($app) {
            return $app->id;
        }

        $app = new Application;
        $app->name = $application;
        $app->save();

        return $app->id;
    }

    public function insertResults($application_id, $branch, $parsedResults, $stats, $file)
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
            Log::debug('ERROR***** inserting record, ' . $e);
        }

        if ($result) {
            return $result->id;
        }

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
        //return view('emails.results', compact('result'));    
    }
}
