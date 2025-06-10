<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\Ports\PasswordHasherPortInterface;
use App\Domain\Ports\TokenManagerPortInterface;
use App\Domain\Ports\ImageManagerPortInterface;
use App\Infrastructure\Repositories\EloquentAdminRepository;
use App\Infrastructure\Repositories\EloquentProfileRepository;
use App\Infrastructure\Repositories\EloquentCommentRepository;
use App\Infrastructure\Adapters\LaravelPasswordHasherAdapter;
use App\Infrastructure\Adapters\LaravelTokenManagerAdapter;
use App\Infrastructure\Adapters\LaravelImageManagerAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Liaison des interfaces repositories avec leurs implémentations
        $this->app->bind(AdminRepositoryInterface::class, EloquentAdminRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, EloquentProfileRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, EloquentCommentRepository::class);
        
        // Liaison des ports avec leurs adaptateurs
        $this->app->bind(PasswordHasherPortInterface::class, LaravelPasswordHasherAdapter::class);
        $this->app->bind(TokenManagerPortInterface::class, LaravelTokenManagerAdapter::class);
        $this->app->bind(ImageManagerPortInterface::class, LaravelImageManagerAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
