<?php

namespace KieranFYI\Tests\Media\Core\Models;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Media\Core\Traits\HasMediaTrait;

class HasMediaStorageModel extends Model
{
    use HasMediaTrait;

    /**
     * @var string
     */
    public string $storage = 'images';
}