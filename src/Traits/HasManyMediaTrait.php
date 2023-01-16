<?php

namespace KieranFYI\Media\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use KieranFYI\Media\Core\Facades\MediaStorage;
use KieranFYI\Media\Core\Models\Media;

/**
 * @mixin Model
 */
trait HasManyMediaTrait
{

    /**
     * @return MorphToMany
     */
    public function media(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model');
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

}