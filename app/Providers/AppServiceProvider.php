<?php

namespace App\Providers;

use App\Models\PlatformSetting;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Event;
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

            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('seller') || $request->is('seller/*')) {
                return route('seller.login');
            }

            return route('login');
        });

        Event::listen(function (Login $event): void {
            $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
        });

        View::composer('layouts.navigation', function ($view): void {
            $user = auth()->user();

            $view->with('previewNotifications', $user ? $user->notifications()->latest()->take(5)->get() : collect());
        });

        View::composer('layouts.customer-sidebar', function ($view): void {
            $view->with('supportEmail', PlatformSetting::current()->support_email);
        });
    }
}
