<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Interfaces\ApplicationInterface;
use App\Repository\ApplicationRepository;
use App\Repository\Interfaces\ResultsInterface;
use App\Repository\ResultsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ApplicationInterface::class, ApplicationRepository::class);
        $this->app->bind(ResultsInterface::class, ResultsRepository::class);
    }
}
