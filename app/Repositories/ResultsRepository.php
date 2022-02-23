<?php 

namespace App\Repositories;

use App\Constants;
use Carbon\Carbon;
use App\Models\Result;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\ResultsInterface;


class ResultsRepository implements  ResultsInterface
{
    public function getFirstResultById(int $id): ?Result
    {
        return Result::SelectResults()
        ->where('id', $id)
        ->with('application')
        ->first();
    }

    public function countAppResults(int $id): int
    {
        return Result::SelectResults()
        ->where('application_id', $id)
        ->count();
    }

    public function countAppFailures(int $id): int
    {
        return Result::SelectResults()
        ->where('application_id', $id)
        ->where('assertions_failed', '>=', 1)
        ->count();
    }

    public function countTotalTestsInTwentyFourHours(): int
    {
        return Result::where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function countTotalResultsByBranch(string $branch): int 
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function countTotalFailures(string $branch): int
    {
        return Result::SelectResults()
            ->where('branch', '=', $branch)
            ->where('assertions_failed', '>=', 1)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    public function insertNewResult(
        int $application_id, 
        string $branch, 
        string $parsedResults, 
        array $stats
    ): ?int
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
            Log::error(Constants::ERROR_INSERT . ', ' . $e);
        }

        if ($result)
            return $result->id;

        return false;
    }
}
