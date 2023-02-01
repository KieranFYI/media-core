<?php

namespace KieranFYI\Tests\Media\Core\Unit\Listeners;

use KieranFYI\Media\Core\Listeners\RegisterRolesListener;
use KieranFYI\Roles\Core\Services\Register\RegisterRole;
use KieranFYI\Tests\Media\Core\TestCase;

class RegisterRolesListenerTest extends TestCase
{

    public function testHandle()
    {
        $listener = new RegisterRolesListener();
        $listener->handle();
        $this->assertArrayHasKey('Media Manager', RegisterRole::roles());
    }
}