<?php

namespace KieranFYI\Media\Core\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use KieranFYI\Media\Core\Listeners\RegisterPermissionListener;
use KieranFYI\Media\Core\Listeners\RegisterRolesListener;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use KieranFYI\Media\Core\Policies\MediaPolicy;
use KieranFYI\Media\Core\Services\Storage\MediaStorage;
use KieranFYI\Roles\Core\Events\Register\RegisterPermissionEvent;
use KieranFYI\Roles\Core\Events\Register\RegisterRoleEvent;
use KieranFYI\Roles\Core\Traits\Policies\RegistersPoliciesTrait;
use Mimey\MimeTypes;

class MediaCorePackageServiceProvider extends ServiceProvider
{
    use RegistersPoliciesTrait;

    /**
     * @var string[]
     */
    public $policies = [
        Media::class => MediaPolicy::class
    ];

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $root = __DIR__ . '/../..';

        $this->publishes([
            $root . '/config/media.php' => config_path('media.php'),
        ], ['roles', 'roles-config']);

        $this->mergeConfigFrom($root . '/config/media.php', 'media');

        $this->app->bind('mimeTypes', MimeTypes::class);
        $this->app->bind('mediaStorage', MediaStorage::class);

        $this->loadMigrationsFrom($root . '/database/migrations');

        $this->registerPolicies();

        Event::listen(RegisterPermissionEvent::class, RegisterPermissionListener::class);
        Event::listen(RegisterRoleEvent::class, RegisterRolesListener::class);
    }

    /**
     * @return void
     */
    private function registerServiceEndpoints(): void
    {
        $endpoints = config('service.endpoints');
        if (!empty(config('media.endpoint'))) {
            $endpoint = config('media.endpoint');
            if (!isset($endpoints[$endpoint])) {
                $endpoints[$endpoint] = [];
            }
            $endpoints[$endpoint] = array_merge($endpoints[$endpoint], [
                Media::class,
                MediaVersion::class
            ]);
        }

        config(['service.endpoints' => $endpoints]);
    }
}
