<?php

namespace KieranFYI\Tests\Media\Core\Models;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Media\Core\Traits\HasManyMediaTrait;

class HasManyMediaStorageModel extends Model
{
    use HasManyMediaTrait;

    /**
     * @var string
     */
    public string $storage = 'images';
}