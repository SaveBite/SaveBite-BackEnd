<?php

namespace App\Providers;

use App\Repository\Eloquent\Repository;
use App\Repository\RepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repository\Eloquent\OtpRepository;
use App\Repository\OtpRepositoryInterface;
use App\Repository\ChatRepositoryInterface;
use App\Repository\Eloquent\ChatRepository;
use App\Repository\Eloquent\RoleRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\RoleRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\Eloquent\ManagerRepository;
use App\Repository\Eloquent\ProductRepository;
use App\Repository\ManagerRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\Eloquent\SettingsRepository;
use App\Repository\SettingsRepositoryInterface;
use App\Repository\Eloquent\PermissionRepository;
use App\Repository\PermissionRepositoryInterface;
use App\Repository\ChatMessageRepositoryInterface;
use App\Repository\Eloquent\ChatMessageRepository;
use App\Repository\Eloquent\LoginAnswerRepository;
use App\Repository\LoginAnswerRepositoryInterface;
use App\Repository\Eloquent\EncodedImageRepository;
use App\Repository\EncodedImageRepositoryInterface;
use App\Repository\Eloquent\TrackingProductRepository;
use App\Repository\Eloquent\UpcomingReorderRepository;
use App\Repository\TrackingProductRepositoryInterface;
use App\Repository\UpcomingReorderRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RepositoryInterface::class, Repository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class , SettingsRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->singleton(ManagerRepositoryInterface::class, ManagerRepository::class);
        $this->app->singleton(EncodedImageRepositoryInterface::class, EncodedImageRepository::class);
        $this->app->singleton(LoginAnswerRepositoryInterface::class, LoginAnswerRepository::class);
        $this->app->singleton(OtpRepositoryInterface::class, OtpRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ChatRepositoryInterface::class, ChatRepository::class);
        $this->app->singleton(ChatMessageRepositoryInterface::class, ChatMessageRepository::class);
        $this->app->singleton(TrackingProductRepositoryInterface::class, TrackingProductRepository::class);
        $this->app->singleton(UpcomingReorderRepositoryInterface::class, UpcomingReorderRepository::class);


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
