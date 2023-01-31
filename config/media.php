<?php

use KieranFYI\Media\Core\Models\Media;

return [
    'default' => 'default',

    'storages' => [
        'default' => [
            'disposition' => Media::DISPOSITION_ATTACHMENT,
            'disk' => 'local',
            'root' => 'default'
        ],

        'images' => [
            'disposition' => Media::DISPOSITION_INLINE,
            //'job' => ImageMediaJob::class,
            'disk' => 'local',
            'root' => 'images',
            'sizes' => [
                16,
                32,
                64,
                128,
                256,
                320,
                640,
                854,
                1280,
                1920,
                2560,
                3840
            ]
        ],

        'videos' => [
            'disposition' => Media::DISPOSITION_INLINE,
            //'job' => VideoMediaService::class,
            'disk' => 'local',
            'root' => 'videos',
            'permission' => 'View Media',
            'sizes' => [
                [
                    'title' => '10p',
                    'width' => 16,
                    'height' => 10,
                    'bitrate' => 70,
                    'audio_bitrate' => 8
                ],
                [
                    'title' => '144p',
                    'width' => 256,
                    'height' => 144,
                    'bitrate' => 1000,
                    'audio_bitrate' => 44
                ],
                [
                    'title' => '240p',
                    'width' => 320,
                    'height' => 240,
                    'bitrate' => 1500,
                    'audio_bitrate' => 44
                ],
                [
                    'title' => '360p',
                    'width' => 640,
                    'height' => 360,
                    'bitrate' => 2100,
                    'audio_bitrate' => 128
                ],
                [
                    'title' => '480p',
                    'width' => 854,
                    'height' => 480,
                    'bitrate' => 4000,
                    'audio_bitrate' => 160
                ],
                [
                    'title' => '720p',
                    'width' => 1280,
                    'height' => 720,
                    'bitrate' => 7000,
                    'audio_bitrate' => 160
                ],
                [
                    'title' => '1080p',
                    'width' => 1920,
                    'height' => 1080,
                    'bitrate' => 11200,
                    'audio_bitrate' => 256

                ],
                [
                    'title' => '1440p',
                    'width' => 2560,
                    'height' => 1440,
                    'bitrate' => 24000,
                    'audio_bitrate' => 256
                ],
                [
                    'title' => '4K',
                    'width' => 3840,
                    'height' => 2160,
                    'bitrate' => 75000,
                    'audio_bitrate' => 320
                ]
            ]
        ]
    ],

    'content_types' => [
        'videos' => [
            'video/x-flv', 'application/x-mpegurl', 'video/mp2t',
            'video/3gpp', 'video/quicktime', 'video/x-msvideo',
            'video/x-ms-wmv', 'video/webm', 'video/mp4', 'image/gif'
        ],
        'images' => [
            'image/jpeg', 'image/png', 'image/bmp', 'image/webp'
        ],
    ]
];