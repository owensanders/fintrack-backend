<?php

namespace App\Providers;

use App\Interfaces\UserExpenseRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserExpenseRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserExpenseRepositoryInterface::class, UserExpenseRepository::class);
    }
}
