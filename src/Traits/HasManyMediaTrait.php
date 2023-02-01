<?php

namespace KieranFYI\Media\Core\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use KieranFYI\Media\Core\Facades\MediaStorage;
use KieranFYI\Media\Core\Models\Media;
use TypeError;

/**
 * @property Collection $media
 * @property string $storage;
 *
 * @mixin Model
 */
trait HasManyMediaTrait
{

    /**
     * @return MorphMany
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * @param File|UploadedFile|string $file
     * @return Media
     */
    public function attachMedia(File|UploadedFile|string $file): Media
    {
        $media = MediaStorage::storage($this->storage())->store($file, $this);
        if ($this->relationLoaded('media')) {
            $this->getRelation('media')->add($media);
        }
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