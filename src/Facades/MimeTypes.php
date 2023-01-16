<?php

namespace KieranFYI\Media\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null getMimeType(string $extension)
 * @method static string|null getExtension(string $mime_type)
 * @method static array getAllMimeTypes(string $extension)
 * @method static array getAllExtensions(string $extension)
 *
 * @see \Mimey\MimeTypes
 */
class MimeTypes extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mimeTypes';
    }
}