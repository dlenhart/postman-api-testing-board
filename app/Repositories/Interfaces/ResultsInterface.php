<?php

namespace App\Repositories\Interfaces;

use App\Models\Result;


interface ResultsInterface
{
    public function getFirstResultById(int $id): ?Result;
    public function insertNewResult(
        int $application_id,
        string $branch,
        string $parsedResults,
        array $stats
    ): ?int;
}
