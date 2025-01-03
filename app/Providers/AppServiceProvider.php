<?php

namespace App\Providers;

use App\Interfaces\AuthenticationServiceInterface;
use App\Interfaces\UserExpensesServiceInterface;
use App\Interfaces\UserSavingsServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Services\AuthenticationService;
use App\Services\UserExpensesService;
use App\Services\UserSavingsService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
        $this->app->bind(UserExpensesServiceInterface::class, UserExpensesService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserSavingsServiceInterface::class, UserSavingsService::class);
    }
}
