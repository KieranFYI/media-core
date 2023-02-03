<?php

namespace KieranFYI\Tests\Media\Core\Unit\Services\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use KieranFYI\Media\Core\Exceptions\InvalidMediaStorageException;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use KieranFYI\Media\Core\Services\Storage\MediaStorage;
use KieranFYI\Tests\Media\Core\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function testDispositions()
    {
        $this->assertEquals('inline', MediaStorage::DISPOSITION_INLINE);
        $this->assertEquals('attachment', MediaStorage::DISPOSITION_ATTACHMENT);
    }

    public function testConfig()
    {
        $storage = new MediaStorage();
        $expected = [
            'disposition' => MediaStorage::DISPOSITION_ATTACHMENT,
            'disk' => 'local',
            'root' => 'default'
        ];
        $this->assertEquals($expected, $storage->config());
    }

    public function testDisposition()
    {
        $storage = new MediaStorage();
        $this->assertEquals(MediaStorage::DISPOSITION_ATTACHMENT, $storage->disposition());
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

    public function testResponse()
    {
        $storage = new MediaStorage();
        $this->expectException(HttpException::class);
        $storage->response(new MediaVersion(['extension' => 'png']), 'test');
    }

    public function testResponseWithVersion()
    {
        $this->artisan('migrate');
        $storage = new MediaStorage();
        $media = $storage->store(UploadedFile::fake()->image('test.png'));
        $response = $storage->response($media->versions->first(), 'png');
        $this->assertInstanceOf(StreamedResponse::class, $response);
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