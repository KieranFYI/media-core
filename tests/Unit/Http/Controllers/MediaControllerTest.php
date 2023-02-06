<?php

namespace KieranFYI\Tests\Media\Core\Unit\Http\Controllers;

use Illuminate\Http\UploadedFile;
use KieranFYI\Media\Core\Facades\MediaStorage;
use KieranFYI\Media\Core\Http\Controllers\MediaVersionController;
use KieranFYI\Tests\Media\Core\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaControllerTest extends TestCase
{

    /**
     * @var MediaVersionController
     */
    private MediaVersionController $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new MediaVersionController();
    }

    public function testShow()
    {
        $this->artisan('migrate');
        $media = MediaStorage::store(UploadedFile::fake()->image('test.png'));
        $response = $this->controller->show($media, $media->versions->first(), 'png');
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

}