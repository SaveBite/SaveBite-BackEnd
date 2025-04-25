<?php

namespace App\Providers;

use App\Http\Services\Api\V1\Auth\AuthMobileService;
use App\Http\Services\Api\V1\Auth\AuthService;
use App\Http\Services\Api\V1\Auth\AuthWebService;
use App\Http\Services\Api\V1\Chat\ChatMobileService;
use App\Http\Services\Api\V1\Chat\ChatService;
use App\Http\Services\Api\V1\Chat\ChatWebService;
use App\Http\Services\Api\V1\LoginAnswer\LoginAnswerMobileService;
use App\Http\Services\Api\V1\LoginAnswer\LoginAnswerService;
use App\Http\Services\Api\V1\LoginAnswer\LoginAnswerWebService;
use App\Http\Services\Api\V1\Product\ProductMobileService;
use App\Http\Services\Api\V1\Product\ProductService;
use App\Http\Services\Api\V1\Product\ProductWebService;
use Illuminate\Support\ServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{
    private const VERSIONS = [1];
    private const PLATFORMS = ['website', 'mobile'];
    private const DEFAULT_VERSION = 1;
    private const DEFAULT_PLATFORM = 'website';
    private const SERVICES = [
        1 => [
            AuthService::class => [
                AuthWebService::class,
                AuthMobileService::class
            ],
            LoginAnswerService::class=>[
                LoginAnswerWebService::class,
                LoginAnswerMobileService::class,
            ],
            ProductService::class => [
                ProductWebService::class,
                ProductMobileService::class,
            ],
            ChatService::class => [
                ChatMobileService::class,
                ChatWebService::class,
            ]
        ],
    ];
    private ?int $version;
    private ?string $platform;

    public function __construct($app)
    {
        parent::__construct($app);

        foreach (self::VERSIONS as $version) {
            foreach (self::PLATFORMS as $platform) {
                $pattern = app()->isProduction()
                    ? "v$version/$platform/*"
                    : "api/v$version/$platform/*";

                if (request()->is($pattern)) {
                    $this->version = $version;
                    $this->platform = $platform;
                    return;
                }
            }
        }

        $this->version = self::DEFAULT_VERSION;
        $this->platform = self::DEFAULT_PLATFORM;
    }

    private function getTargetService(array $services)
    {
        foreach ($services as $service) {
            if ($service::platform() == $this->platform) {
                return $service;
            }
        }

        return $services[0];
    }

    private function initiate(): void
    {
        $services = self::SERVICES[$this->version] ?? [];

        foreach ($services as $abstractService => $targetServices) {
            $this->app->singleton($abstractService, $this->getTargetService($targetServices));
        }
    }

    public function register(): void
    {
        $this->initiate();
    }

    public function boot(): void
    {
        //
    }
}
