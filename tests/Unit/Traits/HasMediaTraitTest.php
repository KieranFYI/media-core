<?php

namespace KieranFYI\Tests\Media\Core\Unit\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Schema;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Tests\Media\Core\Models\HasMediaModel;
use KieranFYI\Tests\Media\Core\Models\HasMediaStorageInvalidModel;
use KieranFYI\Tests\Media\Core\Models\HasMediaStorageModel;
use KieranFYI\Tests\Media\Core\TestCase;
use TypeError;

class HasMediaTraitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Schema::create('has_media_models', function ($table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });
        Schema::create('has_media_storage_models', function ($table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });
    }

    public function testMedia()
    {
        $model = HasMediaModel::create([]);
        $this->assertInstanceOf(MorphOne::class, $model->media());
        $this->assertNull($model->media);
        $model->attachMedia(__DIR__ . '/../../files/textfile.txt');
        $this->assertInstanceOf(Media::class, $model->media);
    }

    public function testMediaReload()
    {
        $model = HasMediaModel::create([]);
        $this->assertInstanceOf(MorphOne::class, $model->media());
        $this->assertNull($model->media);
        $model->attachMedia(__DIR__ . '/../../files/textfile.txt');
        $model->load('media');
        $this->assertInstanceOf(Media::class, $model->media);
    }

    public function testStorage()
    {
        $model = new HasMediaModel();
        $this->assertEquals('default', $model->storage());
    }

    public function testStorageCustom()
    {
        $model = new HasMediaStorageModel();
        $this->assertEquals('images', $model->storage());
    }

    public function testStorageInvalid()
    {
        $model = new HasMediaStorageInvalidModel();
        $this->expectException(TypeError::class);
        $model->storage();
    }
}