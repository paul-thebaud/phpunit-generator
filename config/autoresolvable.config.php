<?php

namespace PhpUnitGen;

use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Parser\PhpParser;
use PhpUnitGen\Renderer\PhpFileRenderer;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;
use PhpUnitGen\Report\Report;
use PhpUnitGen\Report\ReportInterface\ReportInterface;

return [
    PhpParserInterface::class       => PhpParser::class,
    ExecutorInterface::class        => Executor::class,
    ReportInterface::class          => Report::class,
    PhpFileRendererInterface::class => PhpFileRenderer::class
];