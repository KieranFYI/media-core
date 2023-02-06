<?php

namespace KieranFYI\Media\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use KieranFYI\Media\Core\Facades\MediaStorage;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MediaVersionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Media::class, 'media');
    }

    /**
     * Display the specified resource.
     *
     * @param MediaVersion $version
     * @param string $extension
     * @return StreamedResponse
     * @throws Throwable
     */
    public function show(Media $media, MediaVersion $version, string $extension): StreamedResponse
    {
        return MediaStorage::response($version, $extension);
    }
}