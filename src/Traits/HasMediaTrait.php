<?php

namespace KieranFYI\Media\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use KieranFYI\Media\Core\Facades\MediaStorage;
use KieranFYI\Media\Core\Models\Media;
use TypeError;

/**
 * @mixin Model
 */
trait HasMediaTrait
{

    /**
     * @return MorphTo
     */
    public function media(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param UploadedFile $file
     * @return Media
     */
    public function attachMedia(UploadedFile $file): Media
    {
        $media = MediaStorage::storage($this->storage())->store($file, $this);
        $this->setRelation('media', $media);
        return $media;
    }

    /**
     * @return string
     */
    public function storage(): string
    {
        if (property_exists($this, 'storage')) {
            if (!is_string($this->storage)) {
                throw new TypeError(self::class . '::storage(): Property (storage) must be of type string');
            }

            return $this->storage;
        }
        return config('media.default', 'default');
    }
}