<?php

namespace KieranFYI\Media\Core\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method static \KieranFYI\Media\Core\Services\Storage\MediaStorage storage(?string $storage = null)
 * @method static mixed config(?string $key = null, mixed $default = null)
 * @method static bool isPublic()
 * @method static string disposition()
 * @method static string handler()
 * @method static Media store(UploadedFile $file, Model $parent = null)
 * @method static StreamedResponse response(MediaVersion $version, string $extension)
 */
class MediaStorage extends Facade
{
    /**
     * Indicates if the resolved instance should be cached.
     *
     * @var bool
     */
    protected static $cached = false;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mediaStorage';
    }
}