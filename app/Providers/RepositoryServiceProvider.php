<?php

namespace App\Providers;

use App\Repositories\AppointmentRepository;
use App\Repositories\AuthRepository;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\SubscriptionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}