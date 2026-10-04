<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\View;
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
        Authenticate::redirectUsing(function ($request) {
            session()->flash('status', __('Connectez-vous pour accéder à cette fonctionnalité.'));

            return route('login');
        });

        View::composer('layouts.navigation', function ($view): void {
            $user = auth()->user();

            $view->with('previewNotifications', $user ? $user->notifications()->latest()->take(5)->get() : collect());
        });
    }
}
