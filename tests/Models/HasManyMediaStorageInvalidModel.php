<?php

namespace KieranFYI\Tests\Media\Core\Models;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Media\Core\Traits\HasManyMediaTrait;

class HasManyMediaStorageInvalidModel extends Model
{
    use HasManyMediaTrait;

    /**
     * @var array
     */
    public array $storage = [];
}