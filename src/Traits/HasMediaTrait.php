<?php

namespace KieranFYI\Media\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use KieranFYI\Media\Core\Facades\MediaStorage;
use KieranFYI\Media\Core\Models\Media;
use TypeError;

/**
 * @property Media $media
 *
 * @mixin Model
 */
trait HasMediaTrait
{

    /**
     * @return MorphOne
     */
    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }

    /**
     * @param UploadedFile|string $file
     * @return Media
     */
    public function attachMedia(File|UploadedFile|string $file): Media
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