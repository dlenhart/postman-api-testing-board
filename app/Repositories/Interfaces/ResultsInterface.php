<?php

namespace App\Repositories\Interfaces;

use App\Models\Result;


interface ResultsInterface
{
    public function getFirstResultById(int $id): ?Result;
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
