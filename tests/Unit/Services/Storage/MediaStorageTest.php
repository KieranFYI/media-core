<?php

namespace KieranFYI\Tests\Media\Core\Unit\Services\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use KieranFYI\Media\Core\Exceptions\InvalidMediaStorageException;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use KieranFYI\Media\Core\Services\Storage\MediaStorage;
use KieranFYI\Tests\Media\Core\TestCase;

class MediaStorageTest extends TestCase
{

    public function testStorage()
    {
        $this->expectNotToPerformAssertions();
        new MediaStorage();
    }

    public function testStorageInvalid()
    {
        $this->expectException(InvalidMediaStorageException::class);
        new MediaStorage('test');
    }

    public function testConfig()
    {
        $storage = new MediaStorage();
        $expected = [
            'disposition' => Media::DISPOSITION_ATTACHMENT,
            'disk' => 'local',
            'root' => 'default'
        ];
        $this->assertEquals($expected, $storage->config());
    }

    public function testDisposition()
    {
        $storage = new MediaStorage();
        $this->assertEquals(Media::DISPOSITION_ATTACHMENT, $storage->disposition());
    }

    /**
     * @throws InvalidMediaStorageException
     */
    public function testStream()
    {
        $this->artisan('migrate');

        $storage = new MediaStorage();
        $media = $storage->store(__DIR__ . '/../../../files/textfile.txt');
        /** @var MediaVersion $version */
        $version = $media->versions->first();
        $this->assertIsResource($storage->stream($version));
    }

    public function testDisk()
    {
        $storage = new MediaStorage();
        $this->assertInstanceOf(Filesystem::class, $storage->disk());
    }

    public function fixPath()
    {
        $storage = new MediaStorage();
        $this->assertEquals('default/test', $storage->fixPath('test'));
    }

    public function testStore()
    {
        $this->artisan('migrate');

        $storage = new MediaStorage();
        $media = $storage->store(__DIR__ . '/../../../files/textfile.txt');
        $this->assertInstanceOf(Media::class, $media);
    }

    public function testStoreUploadedFile()
    {
        $this->artisan('migrate');

        $storage = new MediaStorage();
        $media = $storage->store(UploadedFile::fake()->image('test.jpg'));
        $this->assertInstanceOf(Media::class, $media);
    }

}