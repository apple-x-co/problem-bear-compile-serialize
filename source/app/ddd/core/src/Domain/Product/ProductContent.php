<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

final class ProductContent
{
    public function __construct(
        public readonly string $videoUrl,
        public readonly string $description,
        public readonly string $volume,
        public readonly string $countryOfOrigin,
        public readonly string $specification,
    ) {
    }

    public static function reconstruct(
        string $videoUrl,
        string $description,
        string $volume,
        string $countryOfOrigin,
        string $specification,
    ): ProductContent {
        return new self(
            $videoUrl,
            $description,
            $volume,
            $countryOfOrigin,
            $specification,
        );
    }
}
