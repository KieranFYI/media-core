<?php

use Illuminate\Support\Facades\Route;
use KieranFYI\Admin\Facades\Admin;
use KieranFYI\Media\Core\Http\Controllers\MediaVersionController;

Admin::route(function () {
    Route::get('media/version/{media}/{version}.{extension}', [MediaVersionController::class, 'show'])
        ->name('media.version.show');
});