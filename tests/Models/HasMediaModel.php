<?php

namespace KieranFYI\Tests\Media\Core\Models;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Media\Core\Traits\HasMediaTrait;

class HasMediaModel extends Model
{
    use HasMediaTrait;
}