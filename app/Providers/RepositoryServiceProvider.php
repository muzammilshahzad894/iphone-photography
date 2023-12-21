<?php

namespace App\Providers;

use App\Interfaces\AchievementRepositoryInterface;
use App\Models\Achievement;
use App\Repositories\AchievementRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            AchievementRepositoryInterface::class,
            fn() => new AchievementRepository(new Achievement)
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}