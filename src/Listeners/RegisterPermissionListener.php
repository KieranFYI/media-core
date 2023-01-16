<?php

namespace KieranFYI\Media\Core\Listeners;

use KieranFYI\Media\Core\Policies\MediaPolicy;

class RegisterPermissionListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(): void
    {
        MediaPolicy::register();
    }
}