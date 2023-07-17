<?php

namespace App\Providers;

use App\Domains\User\Observers\UserObserver;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::morphMap([
            'User' => 'App\Models\User',
        ]);
        User::observe(UserObserver::class);
    }
}
