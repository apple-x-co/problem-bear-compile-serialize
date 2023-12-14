<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use Qiq\Compiler;
use Qiq\Compiler\QiqCompiler;
use Ray\Di\AbstractModule;

class QiqProdModule extends AbstractModule
{
    public function __construct(
        private readonly string $cachePath,
        private AbstractModule|null $module = null,
    ) {
        parent::__construct($this->module);
    }

    protected function configure(): void
    {
        $this->bind()->annotatedWith('qiq_cache_path')->toInstance($this->cachePath);
        $this->bind(Compiler::class)->toConstructor(QiqCompiler::class, ['cachePath' => 'qiq_cache_path']);
    }
}
