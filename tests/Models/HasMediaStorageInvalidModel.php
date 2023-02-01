<?php

namespace KieranFYI\Tests\Media\Core\Models;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Media\Core\Traits\HasMediaTrait;

class HasMediaStorageInvalidModel extends Model
{
    use HasMediaTrait;

    /**
     * @var array
     */
    public array $storage = [];
}