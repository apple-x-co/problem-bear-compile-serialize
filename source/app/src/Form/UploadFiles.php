<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

class UploadFiles implements UploadFilesInterface
{
    /** @param array<string, mixed> $uploadedFiles */
    public function __construct(
        private readonly array $uploadedFiles,
    ) {
    }

    /** @return array<string, mixed> */
    public function getUploadedFileMap(): array
    {
        return $this->uploadedFiles;
    }
}
