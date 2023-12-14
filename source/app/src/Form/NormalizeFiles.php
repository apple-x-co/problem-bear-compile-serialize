<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use function array_keys;
use function is_array;

// TODO: psalm, phpstan, phpcs の除外設定を外す
class NormalizeFiles
{
    public function __invoke(array $files): array
    {
        return self::normalize($files);
    }

    private static function normalize(array $files): array
    {
        $normalized = [];

        foreach ($files as $key => $file) {
            if (is_array($file) && isset($file['tmp_name'])) {
                $normalized[$key] = self::normalizeFromSpec($file);
            } elseif (is_array($file)) {
                $normalized[$key] = self::normalize($file);
            }
        }

        return $normalized;
    }

    private static function normalizeFromSpec(array $file): array
    {
        if (is_array($file['tmp_name'])) {
            return self::normalizeFromNestedSpec($file);
        }

        return [
            'name' => $file['name'],
            'type' => $file['type'],
            'tmp_name' => $file['tmp_name'],
            'error' => (int) $file['error'],
            'size' => (int) $file['size'],
        ];
    }

    private static function normalizeFromNestedSpec(array $files = []): array
    {
        $normalized = [];

        foreach (array_keys($files['tmp_name']) as $key) {
            $spec = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key],
            ];
            /** @psalm-suppress InvalidArrayOffset */
            $normalized[$key] = self::normalizeFromSpec($spec);
        }

        return $normalized;
    }
}
