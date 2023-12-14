<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Qiq\Helpers;
use Qiq\Template;
use Ray\Di\Di\Named;
use stdClass;

use function is_array;

class QiqErrorPageRenderer implements RenderInterface
{
    /** @param array<string> $paths */
    public function __construct(
        #[Named('qiq_cache_path')]
        private readonly string|null $cachePath,
        #[Named('qiq_error_view_name')]
        private readonly string $errorViewName,
        #[Named('qiq_extension')]
        private readonly string $extension,
        private readonly Helpers $helpers,
        #[Named('qiq_paths')]
        private readonly array $paths,
    ) {
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function render(ResourceObject $ro): string
    {
        $template = Template::new(
            $this->paths,
            $this->extension,
            $this->cachePath,
            $this->helpers,
        );
        $template->setView($this->errorViewName);
        if (is_array($ro->body) || $ro->body instanceof stdClass) {
            $template->setData($ro->body);
        }

        $ro->view = $template();

        return $ro->view;
    }
}
