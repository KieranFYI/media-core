<?php

namespace KieranFYI\Media\Core\Listeners;

use KieranFYI\Media\Core\Policies\MediaPolicy;
use KieranFYI\Roles\Core\Services\Register\RegisterRole;

class RegisterRolesListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(): void
    {
        RegisterRole::register('Media Manager')
            ->displayOrder(5)
            ->permission(MediaPolicy::class, ['view', 'viewAny', 'create', 'update', 'delete', 'restore']);
    }
}