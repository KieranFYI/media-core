<?php

namespace KieranFYI\Media\Core\Services\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use KieranFYI\Media\Core\Exceptions\InvalidMediaStorageException;
use KieranFYI\Media\Core\Models\Media;
use KieranFYI\Media\Core\Models\MediaVersion;

class MediaStorage
{
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
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->config('public', false);
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
     * @return string
     */
    public function disposition(): string
    {
        return $this->config('disposition', Media::DISPOSITION_ATTACHMENT);
    }

    /**
     * @param string $filename
     * @return resource|null
     */
    public function stream(string $filename)
    {
        return $this->disk()->readStream($this->fileName($filename));
    }

    /**
     * @return Filesystem
     */
    private function disk(): Filesystem
    {
        if (is_null($this->disk)) {
            $this->disk = Storage::disk($this->config('disk'));
        }
        return $this->disk;
    }

    /**
     * @param string $filename
     * @return string
     */
    private function fileName(string $filename)
    {
        return trim(implode('/', [$this->config('root', ''), $filename]), '/');
    }

    /**
     * @param UploadedFile $file
     * @param Model|null $parent
     * @return Media
     */
    public function store(UploadedFile $file, Model $parent = null): Media
    {
        return DB::transaction(function () use ($file, $parent) {

            $fileName = preg_replace('/[^A-Za-z0-9\-_.]/', '-', $file->getClientOriginalName());
            $contentType = $file->getMimeType();

            $media = new Media([
                'storage' => $this->storage,
                'file_name' => $fileName
            ]);
            $media->model()->associate($parent);
            $media->user()->associate(Auth::user());
            $media->save();

            $mediaVersion = new MediaVersion([
                'name' => 'default',
                'storage' => $this->storage,
                'content_type' => $contentType
            ]);
            $media->versions()->save($mediaVersion);

            $this->disk()->putFileAs($this->config('root', ''), $file, $mediaVersion->file_name);

            return $media;
        });
    }
}