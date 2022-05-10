<?php

namespace App\Repositories\Interfaces;

use App\Models\Application;
use Illuminate\Database\Eloquent\Collection;

interface ApplicationInterface
{
    public function getApplicationByName(String $name): ?Application;
    public function getTotalApplications(): int;
    public function getAllApplications(): Collection;
    public function create(String $name): Int;
}