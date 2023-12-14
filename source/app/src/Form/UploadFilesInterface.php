<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

interface UploadFilesInterface
{
    /** @return array<string, mixed> */
    public function getUploadedFileMap(): array;
}
