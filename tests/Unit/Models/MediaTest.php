<?php

namespace KieranFYI\Tests\Media\Core\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use KieranFYI\Logging\Traits\LoggingTrait;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use KieranFYI\Media\Core\Services\Storage\MediaStorage;
use KieranFYI\Misc\Traits\ImmutableTrait;
use KieranFYI\Misc\Traits\KeyedTitle;
use KieranFYI\Roles\Core\Traits\BuildsAccess;
use KieranFYI\Services\Core\Traits\Serviceable;
use KieranFYI\Tests\Media\Core\Models\TestModel;
use KieranFYI\Tests\Media\Core\TestCase;

class MediaTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new Media();
        Schema::create('test_models', function ($table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * @var Media
     */
    private Media $model;

    public function testClass()
    {
        $this->assertInstanceOf(Model::class, $this->model);
    }

    public function testTraits()
    {
        $uses = class_uses_recursive(Media::class);
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
            'deleted_at', 'model_id', 'model_type', 'updated_at'
        ];
        $this->assertEquals($expected, $this->model->whitelist());
    }

    public function testFillable()
    {
        $expected = [
            'file_name'
        ];

        $this->assertEquals($expected, $this->model->getFillable());
    }

    public function testVisible()
    {
        $expected = [
            'id', 'file_name', 'modal', 'media', 'versions', 'created_at'
        ];

        $this->assertEquals($expected, $this->model->getVisible());
    }

    public function testTitle()
    {
        $this->model->file_name = 'Test';
        $this->assertEquals('Test', $this->model->getTitleAttribute());
    }

    public function testModel()
    {
        $this->artisan('migrate');
        $this->assertInstanceOf(MorphTo::class, $this->model->model());
        $testModel = TestModel::create([]);
        $this->model->model()->associate($testModel);
        $testModel->is($this->model->model);
    }

    public function testUser()
    {
        $this->artisan('migrate');
        $this->assertInstanceOf(MorphTo::class, $this->model->user());
        $testModel = TestModel::create([]);
        $this->model->user()->associate($testModel);
        $testModel->is($this->model->user);
    }

    public function testGetAdminUrlAttribute()
    {
        $this->artisan('migrate');

        $storage = new MediaStorage();
        $media = $storage->store(__DIR__ . '/../../files/textfile.txt');
        /** @var MediaVersion $version */
        $version = $media->versions->first();
        $this->assertIsString($media->getAdminUrlAttribute());
        $this->assertIsString($media->admin_url);
        $this->assertEquals(route('admin.media.version.show', [
            'version' => $version,
            'extension' => $version->extension,
        ]), $media->admin_url);
    }
}