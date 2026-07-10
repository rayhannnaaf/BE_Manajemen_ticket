<?php

namespace App\Providers;

use App\Contracts\KategoriKonserServiceInterface;
use App\Contracts\KonserServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\TicketServiceInterface;
use App\Services\KategoriKonserService;
use App\Services\KonserService;
use App\Services\OrderService;
use App\Services\TicketService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(KategoriKonserServiceInterface::class, KategoriKonserService::class);
        $this->app->bind(KonserServiceInterface::class, KonserService::class);
        $this->app->bind(TicketServiceInterface::class, TicketService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}