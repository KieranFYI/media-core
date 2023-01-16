<?php

namespace KieranFYI\Media\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use KieranFYI\Logging\Traits\LoggingTrait;
use KieranFYI\Media\Core\Facades\MimeTypes;
use KieranFYI\Misc\Traits\HasKeyTrait;
use KieranFYI\Misc\Traits\ImmutableTrait;
use KieranFYI\Services\Core\Traits\Serviceable;

/**
 * @property string $name
 * @property string $storage
 * @property string $file_hash
 * @property string $content_type
 * @property string $extension
 * @property string $file_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Carbon $deleted_at
 */
class MediaVersion extends Model
{
    use SoftDeletes;
    use ImmutableTrait;
    use LoggingTrait;
    use HasKeyTrait;
    use Serviceable;

    /**
     * @var array|string[]
     */
    public array $whitelist = ['storage', 'deleted_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'storage', 'file_hash', 'content_type'
    ];

    /**
     * @var string[]
     */
    protected $touches = [
        'media'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'media_id', 'storage', 'file_name', 'updated_at', 'deleted_at'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * @return BelongsTo
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * @return string
     */
    public function getExtensionAttribute(): string
    {
        return MimeTypes::getExtension($this->content_type) ?? 'file';
    }

    /**
     * @return string
     */
    public function getFileNameAttribute(): string
    {
        return $this->key . '.' . $this->extension;
    }
}