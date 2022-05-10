<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Repositories\ApplicationRepository;
use App\Repositories\ResultsRepository;
use App\Mail\SendResults;
use App\Helpers\File;
use App\Helpers\Parser;
use App\Constants;


class ImportService
{
    public function __construct(
        ApplicationRepository $applicationRepository,
        ResultsRepository $resultsRepository
    ) {
        $this->applicationRepository = $applicationRepository;
        $this->resultsRepository = $resultsRepository;
    }

    public function import($request): JsonResponse
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'message' => Constants::NO_FILE_PROVIDED,
                'status' => Constants::ERROR,
            ], 400);
        }

        $results = File::extractRunInfo(
            $request->file('file')
        );

        if ($results['extension'] != 'json') {
            return response()->json([
                'message' => Constants::INVALID_EXTENSION,
                'status' => Constants::ERROR,
            ], 400);
        }

        $application_id = $this->findApplicationOrCreate(
            $results['application']
        );
        
        $decoded = $this->getFileContents($request->file('file'));

        if (!isset($decoded['collection'])) {
            return response()->json([
                'message' => Constants::INVALID_FILE,
                'status' => Constants::ERROR,
            ], 400);
        }

        $resultMessages = Parser::buildResults(
            $decoded['collection']['item'],
            $decoded
        );

        $result = $this->resultsRepository
            ->insertNewResult(
                $application_id,
                $results['branch'],
                $resultMessages,
                $decoded['run']
            );

        if ($result) {
            if ($request->email) {
                $this->sendResultsEmail($result, $request->email);
            }

            $data = [
                'application'       => $results['application'] ?? '',
                'application_id'    => $application_id,
                'send_to'           => $request->email ? $request->email : '',
                'result_id'         => $result
            ];

            return response()->json([
                'message' =>  Constants::IMPORT_SUCCESS,
                'status' => Constants::SUCCESS,
                'data' => $data
            ], 200);
        }

        return response()->json([
            'message' => Constants::IMPORT_FAILED,
            'status' => Constants::ERROR,
        ], 500);
    }

    public function findApplicationOrCreate(string $application)
    {
        $app = $this->applicationRepository
            ->getApplicationByName($application);

        if ($app)
            return $app->id;

        return $this->applicationRepository->create($application);
    }

    private function getFileContents(string $file)
    {
        $results = file_get_contents($file, "r")
            or die(Constants::UNABLE_TO_OPEN_FILE);

        return json_decode($results, true);
    }

    public function sendResultsEmail($id, $email): void
    {
        $result = $this->resultsRepository
            ->getFirstResultById($id);

        if ($result)
            Mail::to($email)->send(new SendResults($result));
    }
}
