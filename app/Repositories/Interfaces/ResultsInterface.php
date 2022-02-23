<?php

namespace App\Repositories\Interfaces;

use App\Models\Result;

interface ResultsInterface
{
    public function getFirstResultById(int $id): ?Result;
}