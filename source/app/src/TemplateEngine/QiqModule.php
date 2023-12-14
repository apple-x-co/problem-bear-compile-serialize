<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Resource\RenderInterface;
use Qiq\Catalog;
use Qiq\Compiler;
use Qiq\Compiler\QiqCompiler;
use Qiq\Helper\Html\HtmlHelpers;
use Qiq\Helpers;
use Qiq\Template;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class QiqModule extends AbstractModule
{
    /** @param array<string> $paths */
    public function __construct(
        private readonly array $paths,
        private AbstractModule|null $module = null,
    ) {
        parent::__construct($this->module);
    }

    protected function configure(): void
    {
        $this->bind(Template::class)->toConstructor(
            Template::class,
            [
                'paths' => 'qiq_paths',
                'extension' => 'qiq_extension',
            ],
        );
        $this->bind(Catalog::class)->toConstructor(
            Catalog::class,
            [
                'paths' => 'qiq_paths',
                'extension' => 'qiq_extension',
            ],
        );
        $this->bind(Helpers::class)->to(HtmlHelpers::class);
        $this->bind()->annotatedWith('qiq_cache_path')->toInstance(null);
        $this->bind()->annotatedWith('qiq_extension')->toInstance('.php');
        $this->bind()->annotatedWith('qiq_paths')->toInstance($this->paths);
        $this->bind(RenderInterface::class)->to(QiqRenderer::class)->in(Scope::SINGLETON);
        $this->bind(Compiler::class)->to(QiqCompiler::class);
    }
}
