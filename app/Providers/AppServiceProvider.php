<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Infrastructure\Repositories\EloquentAdminRepository;
use App\Infrastructure\Repositories\EloquentProfileRepository;
use App\Infrastructure\Repositories\EloquentCommentRepository;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
