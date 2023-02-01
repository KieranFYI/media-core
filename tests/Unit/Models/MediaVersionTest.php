<?php

namespace KieranFYI\Tests\Media\Core\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use KieranFYI\Logging\Traits\LoggingTrait;
use KieranFYI\Media\Core\Models\MediaVersion;
use KieranFYI\Media\Core\Services\Storage\MediaStorage;
use KieranFYI\Misc\Traits\ImmutableTrait;
use KieranFYI\Misc\Traits\KeyedTitle;
use KieranFYI\Roles\Core\Traits\BuildsAccess;
use KieranFYI\Services\Core\Traits\Serviceable;
use KieranFYI\Tests\Media\Core\Models\TestModel;
use KieranFYI\Tests\Media\Core\TestCase;

class MediaVersionTest extends TestCase
{


    /**
     * @var MediaVersion
     */
    private MediaVersion $model;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new MediaVersion();
    }

    public function testClass()
    {
        $this->assertInstanceOf(Model::class, $this->model);
    }

    public function testTraits()
    {
        $uses = class_uses_recursive(MediaVersion::class);
        $this->assertContains(SoftDeletes::class, $uses);
        $this->assertContains(ImmutableTrait::class, $uses);
        $this->assertContains(LoggingTrait::class, $uses);
        $this->assertContains(Serviceable::class, $uses);
        $this->assertContains(BuildsAccess::class, $uses);
        $this->assertContains(KeyedTitle::class, $uses);
    }

    public function testWhitelist()
    {
        $expected = [
            'storage', 'deleted_at'
        ];
        $this->assertEquals($expected, $this->model->whitelist());
    }

    public function testFillable()
    {
        $expected = [
            'name', 'storage', 'file_hash', 'content_type', 'key'
        ];

        $this->assertEquals($expected, $this->model->getFillable());
    }

    public function testTouches()
    {
        $expected = [
            'media'
        ];

        $this->assertEquals($expected, $this->model->getTouchedRelations());
    }

    public function testHidden()
    {
        $expected = [
            'media_id', 'storage', 'file_name', 'updated_at', 'deleted_at'
        ];

        $this->assertEquals($expected, $this->model->getHidden());
    }

    public function testCasts()
    {
        $expected = [
            'data' => 'array',
            'id' => 'int',
            'deleted_at' => 'datetime'
        ];

        $this->assertEquals($expected, $this->model->getCasts());
    }

    public function testTitle()
    {
        $this->model->name = 'Test';
        $this->assertEquals('Test', $this->model->getTitleAttribute());
    }

    public function testExtension()
    {
        $this->model->content_type = 'image/png';
        $this->assertIsString($this->model->getExtensionAttribute());
        $this->assertIsString($this->model->extension);
        $this->assertEquals('png', $this->model->getExtensionAttribute());
    }

    public function testFileName()
    {
        $this->model->fill([
            'content_type' => 'image/png',
            'key' => 'test'
        ]);
        $this->assertIsString($this->model->getFileNameAttribute());
        $this->assertIsString($this->model->file_name);
        $this->assertEquals('test.png', $this->model->getFileNameAttribute());
    }

    public function testStream()
    {
        $this->artisan('migrate');

        $storage = new MediaStorage();
        $media = $storage->store(__DIR__ . '/../../files/textfile.txt');
        /** @var MediaVersion $version */
        $version = $media->versions->first();
        $this->assertIsResource($version->stream);
    }
}