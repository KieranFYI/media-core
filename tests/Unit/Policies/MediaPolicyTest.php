<?php

namespace KieranFYI\Tests\Media\Core\Unit\Policies;

use Illuminate\Foundation\Auth\User;
use KieranFYI\Media\Core\Policies\MediaPolicy;
use KieranFYI\Tests\Media\Core\TestCase;

class MediaPolicyTest extends TestCase
{

    /**
     * @var MediaPolicy
     */
    private MediaPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new MediaPolicy();
    }

    public function testViewNullUser()
    {
        $this->assertFalse($this->policy->view(null, new User()));
    }


}