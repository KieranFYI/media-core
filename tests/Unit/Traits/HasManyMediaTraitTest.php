<?php

namespace KieranFYI\Tests\Media\Core\Unit\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Schema;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Tests\Media\Core\Models\HasManyMediaModel;
use KieranFYI\Tests\Media\Core\Models\HasManyMediaStorageInvalidModel;
use KieranFYI\Tests\Media\Core\Models\HasManyMediaStorageModel;
use KieranFYI\Tests\Media\Core\TestCase;
use TypeError;

class HasManyMediaTraitTest extends TestCase
{

    /**
     * @var HasManyMediaModel
     */
    private HasManyMediaModel $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Schema::create('has_many_media_models', function ($table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });

        $this->model = HasManyMediaModel::create([]);
    }

    public function testMedia()
    {
        $this->assertInstanceOf(MorphMany::class, $this->model->media());
        $this->assertInstanceOf(Collection::class, $this->model->media);
        $attachedMedia = $this->model->attachMedia(__DIR__ . '/../../files/textfile.txt');
        $this->assertInstanceOf(Media::class, $attachedMedia);
        $this->assertInstanceOf(Collection::class, $this->model->media);
        $this->assertInstanceOf(Media::class, $this->model->media->first());
        $this->assertTrue($attachedMedia->is($this->model->media->first()));
    }

    public function testMediaReload()
    {
        $this->assertInstanceOf(MorphMany::class, $this->model->media());
        $this->assertInstanceOf(Collection::class, $this->model->media);
        $attachedMedia = $this->model->attachMedia(__DIR__ . '/../../files/textfile.txt');
        $this->model->load('media');
        $this->assertInstanceOf(Media::class, $attachedMedia);
        $this->assertInstanceOf(Collection::class, $this->model->media);
        $this->assertInstanceOf(Media::class, $this->model->media->first());
        $this->assertTrue($attachedMedia->is($this->model->media->first()));
    }

    public function testStorage()
    {
        $model = new HasManyMediaModel();
        $this->assertEquals('default', $model->storage());
    }

    public function testStorageCustom()
    {
        $model = new HasManyMediaStorageModel();
        $this->assertEquals('images', $model->storage());
    }

    public function testStorageInvalid()
    {
        $model = new HasManyMediaStorageInvalidModel();
        $this->expectException(TypeError::class);
        $model->storage();
    }
}