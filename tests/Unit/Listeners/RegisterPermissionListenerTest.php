<?php

namespace KieranFYI\Tests\Media\Core\Unit\Listeners;

use KieranFYI\Media\Core\Listeners\RegisterPermissionListener;
use KieranFYI\Media\Core\Policies\MediaPolicy;
use KieranFYI\Roles\Core\Services\Register\RegisterPermission;
use KieranFYI\Tests\Media\Core\TestCase;

class RegisterPermissionListenerTest extends TestCase
{

    public function testHandle()
    {
        $listener = new RegisterPermissionListener();
        $listener->handle();
        $this->assertContains(MediaPolicy::class, RegisterPermission::policies());
    }
}