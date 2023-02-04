<?php

namespace KieranFYI\Media\Core\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::morphMap([
            'media' => Media::class,
            'mediaVersion' => MediaVersion::class
        ]);

        $root = __DIR__ . '/../..';

        $this->publishes([
            $root . '/config/media.php' => config_path('media.php'),
        ], ['media', 'media-config', 'config']);

        $this->mergeConfigFrom($root . '/config/media.php', 'media');

        $this->app->bind('mimeTypes', MimeTypes::class);
        $this->app->bind('mediaStorage', MediaStorage::class);

        $this->loadMigrationsFrom($root . '/database/migrations');

        $this->registerPolicies();

        Event::listen(RegisterPermissionEvent::class, RegisterPermissionListener::class);
        Event::listen(RegisterRoleEvent::class, RegisterRolesListener::class);
    }
}
