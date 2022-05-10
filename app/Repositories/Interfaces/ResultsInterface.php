<?php

namespace App\Repositories\Interfaces;

use App\Models\Result;
use Illuminate\Database\Eloquent\Builder;


interface ResultsInterface
{
    public function getFirstResultById(int $id): ?Result;
    public function getFirstResultByIdOrFail(int $id): Result;
    public function selectAllwithApplication(): ?Builder;
    public function selectAllByApplicationId(int $id): ?Builder;
    public function countAppResults(int $id): int;
    public function countTotalTestsInTwentyFourHours(): int;
    public function countTotalResultsByBranch(string $branch): int;
    public function countTotalFailures(string $branch): int;
    public function insertNewResult(
        int $application_id,
        string $branch,
        string $parsedResults,
        array $stats
    ): ?int;
}
