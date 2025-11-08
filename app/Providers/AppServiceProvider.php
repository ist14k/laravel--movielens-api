<?php

namespace App\Providers;

use App\Models\Movie;
use App\Models\Review;
use App\Policies\MoviePolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Movie::class, MoviePolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
    }
}
