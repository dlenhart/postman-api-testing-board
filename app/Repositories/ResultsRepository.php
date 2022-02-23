<?php 

namespace App\Repositories;

use App\Models\Result;
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
}
