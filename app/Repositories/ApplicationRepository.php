<?php

namespace App\Repositories;

use App\Models\Application;
use App\Repositories\Interfaces\ApplicationInterface;

class ApplicationRepository implements ApplicationInterface
{
    public function getApplicationByName(String $name): ?Application
    {
        return Application::where('name', $name)->first();
    }

    public function create(string $name): Int
    {
        $app = new Application;
        $app->name = $name;
        $app->save();

        return $app->id;
    }
}