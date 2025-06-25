<?php

namespace App\Providers;

use App\Repositories\TodoRepository;
use App\Repositories\TodoRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        TodoRepositoryInterface::class => TodoRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
