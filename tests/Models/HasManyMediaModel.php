<?php

namespace KieranFYI\Tests\Media\Core\Models;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Media\Core\Traits\HasManyMediaTrait;

class HasManyMediaModel extends Model
{
    use HasManyMediaTrait;
}