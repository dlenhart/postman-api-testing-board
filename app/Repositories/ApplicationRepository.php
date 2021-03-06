<?php

namespace App\Repositories;

use App\Models\Application;
use App\Repositories\Interfaces\ApplicationInterface;
use Illuminate\Database\Eloquent\Collection;

class ApplicationRepository implements ApplicationInterface
{
    public function getApplicationByName(string $name): ?Application
    {
        return Application::where('name', $name)->first();
    }

    public function getTotalApplications(): int 
    {
        return Application::all()->count();
    }

    public function getAllApplications(): Collection
    {
        return Application::all();
    }

    public function create(string $name): Int
    {
        $app = new Application;
        $app->name = $name;
        $app->save();

        return $app->id;
    }
}