<?php

namespace KieranFYI\Media\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use KieranFYI\Logging\Traits\LoggingTrait;
use KieranFYI\Misc\Traits\ImmutableTrait;
use KieranFYI\Misc\Traits\KeyedTitle;
use KieranFYI\Roles\Core\Traits\BuildsAccess;
use KieranFYI\Services\Core\Traits\Serviceable;

/**
 * @property string $file_name
 * @property Collection $versions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Carbon $deleted_at
 */
class Media extends Model
{
    use SoftDeletes;
    use ImmutableTrait;
    use LoggingTrait;
    use Serviceable;
    use BuildsAccess;
    use KeyedTitle;

    /**
     * @var array|string[]
     */
    public array $whitelist = [
        'deleted_at', 'model_id', 'model_type', 'updated_at'
    ];

    /**
     * @var array|string[]
     */
    public $with = [
        'versions'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'file_name'
    ];

    /**
     * @var array|string[]
     */
    protected $visible = [
        'id', 'file_name', 'modal', 'media', 'versions', 'created_at'
    ];

    /**
     * @var string
     */
    protected string $title_key = 'file_name';

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function versions(): HasMany
    {
        return $this->hasMany(MediaVersion::class);
    }
}
