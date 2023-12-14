<?php

declare(strict_types=1);

namespace AppCore\Domain\Store;

final class StoreLogoImage
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $size
     * @param int<1, max> $width
     * @param int<1, max> $height
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly int $size,
        public readonly string $mediaType,
        public readonly int $width,
        public readonly int $height,
        public readonly string $originalFileName,
        public readonly string $url,
        public readonly string $path,
        public readonly string $fileName,
        public readonly string $md5,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $size
     * @param int<1, max> $width
     * @param int<1, max> $height
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        int $size,
        string $mediaType,
        int $width,
        int $height,
        string $originalFileName,
        string $url,
        string $path,
        string $fileName,
        string $md5,
    ): StoreLogoImage {
        return new self(
            $size,
            $mediaType,
            $width,
            $height,
            $originalFileName,
            $url,
            $path,
            $fileName,
            $md5,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }
}
