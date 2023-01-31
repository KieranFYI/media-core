<?php

namespace KieranFYI\Tests\Media\Core\Unit\Traits;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Schema;
use KieranFYI\Tests\Media\Core\Models\HasMediaModel;
use KieranFYI\Tests\Media\Core\TestCase;

class HasMediaTraitTest extends TestCase
{

    /**
     * @var HasMediaModel
     */
    private HasMediaModel $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Schema::create('has_media_models', function ($table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });

        $this->model = HasMediaModel::create([]);
    }

    public function testMedia()
    {
        $this->assertInstanceOf(MorphTo::class, $this->model->media());
        $this->assertNull($this->model->media);
    }
}