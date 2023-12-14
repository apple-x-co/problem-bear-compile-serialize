<?php

declare(strict_types=1);

namespace AppCore\Domain;

final class GeometryLocation
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
    }
}
