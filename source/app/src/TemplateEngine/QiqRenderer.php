<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Qiq\Template;
use Ray\Aop\WeavedInterface;
use ReflectionClass;

use function assert;
use function is_array;
use function is_string;
use function str_replace;
use function strpos;
use function substr;

class QiqRenderer implements RenderInterface
{
    private const LENGTH_OF_RESOURCE_DIR = 13;

    public function __construct(
        private readonly Template $template,
    ) {
    }

    public function render(ResourceObject $ro): string
    {
        $template = clone $this->template;
        $this->setTemplateView($template, $ro);
        assert($ro->body === null || is_array($ro->body));
        $template->setData($ro->body ?? []);

        $ro->view = $template();

        return $ro->view;
    }

    private function setTemplateView(Template $template, ResourceObject $ro): void
    {
        $fileName = $this->getReflection($ro)->getFileName();
        assert(is_string($fileName));

        $pos = strpos($fileName, 'src/Resource/');
        $relativePath = substr($fileName, (int) $pos + self::LENGTH_OF_RESOURCE_DIR);

        $view = str_replace('.php', '', $relativePath);
        $template->setView($view);
    }

    /** @return ReflectionClass<object> */
    private function getReflection(ResourceObject $ro): ReflectionClass
    {
        if ($ro instanceof WeavedInterface) {
            /** @var ReflectionClass<ResourceObject> $parentClass */
            $parentClass = (new ReflectionClass($ro))->getParentClass();

            return $parentClass;
        }

        return new ReflectionClass($ro);
    }
}
