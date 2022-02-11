<?php

namespace App\Repositories\Interfaces;

use App\Models\Application;

interface ApplicationInterface
{
    public function getApplicationByName(String $name): ?Application;
    public function create(String $name): Int;
}