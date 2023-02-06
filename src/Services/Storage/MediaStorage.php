<?php

namespace KieranFYI\Media\Core\Services\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use KieranFYI\Media\Core\Exceptions\InvalidMediaStorageException;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;
use KieranFYI\Misc\Facades\Cacheable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaStorage
{
    const DISPOSITION_INLINE = 'inline';
    const DISPOSITION_ATTACHMENT = 'attachment';

    /**
     * @var string
     */
    private string $storage;

    /**
     * @var array|null
     */
    private ?array $config;

    /**
     * @var Filesystem|null
     */
    private ?Filesystem $disk = null;

    /**
     * @throws InvalidMediaStorageException
     */
    public function __construct(?string $storage = null)
    {
        $this->storage($storage);
    }

    /**
     * Set the current storage config
     *
     * @param string|null $storage
     * @return MediaStorage
     * @throws InvalidMediaStorageException
     */
    public function storage(?string $storage = null): static
    {
        if (is_null($storage)) {
            $storage = config('media.default');
        }
        $this->storage = $storage;
        $this->setConfig();
        return $this;
    }

    /**
     * @throws InvalidMediaStorageException
     */
    private function setConfig(): void
    {
        $this->config = config('media.storages.' . $this->storage);
        if (is_null($this->config)) {
            throw new InvalidMediaStorageException('Invalid config: ' . $this->storage);
        }
    }

    /**
     * @param MediaVersion $version
     * @return resource|null
     */
    public function stream(MediaVersion $version)
    {
        return $this->disk()->readStream($this->fixPath($version->file_name));
    }

    /**
     * @return Filesystem
     */
    public function disk(): Filesystem
    {
        if (is_null($this->disk)) {
            $this->disk = Storage::disk($this->config('disk'));
        }
        return $this->disk;
    }

    /**
     * @param string|null $key
     * @param mixed $default
     * @return array
     */
    public function config(?string $key = null, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function fixPath(string $filename): string
    {
        return trim(implode('/', [$this->config('root', ''), $filename]), '/');
    }

    /**
     * @param MediaVersion $version
     * @param string $extension
     * @return StreamedResponse
     * @throws InvalidMediaStorageException
     */
    public function response(MediaVersion $version, string $extension): StreamedResponse
    {
        Cacheable::cached($version->updated_at);
        if ($version->extension !== $extension) {
            abort(404);
        }
        $version->load('media');

        return response()->stream(
            function () use ($version) {
                while (ob_get_level() > 0) ob_end_flush();
                fpassthru($version->stream);
            },
            headers: [
                'Content-Type' => $version->content_type,
                'Content-Disposition' => $this->storage($version->storage)->disposition() . '; filename="' . $version->file_name . '"'
            ]
        );
    }

    /**
     * @return string
     */
    public function disposition(): string
    {
        return $this->config('disposition', self::DISPOSITION_ATTACHMENT);
    }

    /**
     * @param UploadedFile $file
     * @param Model|null $parent
     * @return Media
     */
    public function store(File|UploadedFile|string $file, Model $parent = null): Media
    {
        return DB::transaction(function () use ($file, $parent) {
            $media = new Media([
                'storage' => $this->storage,
                'file_name' => $this->filename($file)
            ]);
            $media->model()->associate($parent);
            $media->user()->associate(Auth::user());
            $media->save();

            $mediaVersion = new MediaVersion([
                'name' => 'default',
                'storage' => $this->storage,
                'content_type' => $this->contentType($file)
            ]);
            $media->versions()->save($mediaVersion);
            $media->load('versions');

            $this->disk()->putFileAs($this->config('root', ''), $file, $mediaVersion->file_name);

            return $media;
        });
    }

    /**
     * @param File|UploadedFile|string $file
     * @return string|null
     */
    private function filename(File|UploadedFile|string $file): string|null
    {
        if ($file instanceof UploadedFile) {
            $name = preg_replace('/[^A-Za-z0-9\-_.]/', '-', $file->getClientOriginalName());
        } else if ($file instanceof File) {
            $name = preg_replace('/[^A-Za-z0-9\-_.]/', '-', $file->getFilename());
        } else {
            $name = FileFacade::basename($file);
        }

        return empty($name) ? null : $name;
    }

    /**
     * @param File|UploadedFile|string $file
     * @return string|null
     */
    public function contentType(File|UploadedFile|string $file): string|null
    {
        if (method_exists($file, 'getMimeType')) {
            $type = $file->getMimeType();
        } else {
            $type = FileFacade::mimeType($file);
        }

        return empty($type) ? null : $type;
    }
}