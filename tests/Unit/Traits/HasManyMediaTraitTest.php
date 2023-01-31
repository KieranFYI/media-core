<?php

namespace KieranFYI\Tests\Media\Core\Unit\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Schema;
use KieranFYI\Tests\Media\Core\Models\HasManyMediaModel;
use KieranFYI\Tests\Media\Core\TestCase;

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
    }
}