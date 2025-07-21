<?php

namespace App\Providers;
use App\Repositories\Interfaces\CarRepositoryInterface;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Repositories\BrandRepository;

use App\Repositories\CarRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    $this->app->bind(CarRepositoryInterface::class, CarRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
