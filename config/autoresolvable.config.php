<?php

namespace PhpUnitGen;

use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\NodeParser\AttributeNodeParser;
use PhpUnitGen\Parser\NodeParser\ClassNodeParser;
use PhpUnitGen\Parser\NodeParser\FunctionNodeParser;
use PhpUnitGen\Parser\NodeParser\GroupUseNodeParser;
use PhpUnitGen\Parser\NodeParser\InterfaceNodeParser;
use PhpUnitGen\Parser\NodeParser\MethodNodeParser;
use PhpUnitGen\Parser\NodeParser\NamespaceNodeParser;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\AttributeNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ClassNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\FunctionNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\GroupUseNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\InterfaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\MethodNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\NamespaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ParameterNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\PhpFileNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\TraitNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\TypeNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\UseNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ValueNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\ParameterNodeParser;
use PhpUnitGen\Parser\NodeParser\PhpFileNodeParser;
use PhpUnitGen\Parser\NodeParser\TraitNodeParser;
use PhpUnitGen\Parser\NodeParser\TypeNodeParser;
use PhpUnitGen\Parser\NodeParser\UseNodeParser;
use PhpUnitGen\Parser\NodeParser\ValueNodeParser;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Parser\PhpParser;
use PhpUnitGen\Report\Report;
use PhpUnitGen\Report\ReportInterface\ReportInterface;

return [
    PhpParserInterface::class           => PhpParser::class,
    ExecutorInterface::class            => Executor::class,
    ReportInterface::class              => Report::class,
    ValueNodeParserInterface::class     => ValueNodeParser::class,
    TypeNodeParserInterface::class      => TypeNodeParser::class,
    UseNodeParserInterface::class       => UseNodeParser::class,
    GroupUseNodeParserInterface::class  => GroupUseNodeParser::class,
    ParameterNodeParserInterface::class => ParameterNodeParser::class,
    FunctionNodeParserInterface::class  => FunctionNodeParser::class,
    MethodNodeParserInterface::class    => MethodNodeParser::class,
    AttributeNodeParserInterface::class => AttributeNodeParser::class,
    InterfaceNodeParserInterface::class => InterfaceNodeParser::class,
    TraitNodeParserInterface::class     => TraitNodeParser::class,
    ClassNodeParserInterface::class     => ClassNodeParser::class,
    NamespaceNodeParserInterface::class => NamespaceNodeParser::class,
    PhpFileNodeParserInterface::class   => PhpFileNodeParser::class
];