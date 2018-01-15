<?php

namespace PhpUnitGen\Container;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\ContainerInterface\ContainerFactoryInterface;
use PhpUnitGen\Container\ContainerInterface\ContainerInterface;
use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\NodeParser\AttributeNodeParser;
use PhpUnitGen\Parser\NodeParser\ClassNodeParser;
use PhpUnitGen\Parser\NodeParser\FunctionNodeParser;
use PhpUnitGen\Parser\NodeParser\InterfaceNodeParser;
use PhpUnitGen\Parser\NodeParser\MethodNodeParser;
use PhpUnitGen\Parser\NodeParser\NamespaceNodeParser;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\AttributeNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ClassNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\FunctionNodeParserInterface;
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

/**
 * Class ContainerFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(ConfigInterface $config): ContainerInterface
    {
        $container = new Container();

        // Instance dependencies
        $container->setInstance(ConfigInterface::class, $config);
        $container->setInstance(ConsoleConfigInterface::class, $config);
        $container->setInstance(Parser::class, (new ParserFactory())->create(ParserFactory::PREFER_PHP7));

        // Automatically created dependencies and aliases
        $container->set(PhpParserInterface::class, PhpParser::class);
        $container->set(ExecutorInterface::class, Executor::class);

        $container->set(ReportInterface::class, Report::class);

        $container->set(ValueNodeParserInterface::class, ValueNodeParser::class);
        $container->set(TypeNodeParserInterface::class, TypeNodeParser::class);
        $container->set(UseNodeParserInterface::class, UseNodeParser::class);
        $container->set(ParameterNodeParserInterface::class, ParameterNodeParser::class);
        $container->set(FunctionNodeParserInterface::class, FunctionNodeParser::class);
        $container->set(MethodNodeParserInterface::class, MethodNodeParser::class);
        $container->set(AttributeNodeParserInterface::class, AttributeNodeParser::class);
        $container->set(InterfaceNodeParserInterface::class, InterfaceNodeParser::class);
        $container->set(TraitNodeParserInterface::class, TraitNodeParser::class);
        $container->set(ClassNodeParserInterface::class, ClassNodeParser::class);
        $container->set(NamespaceNodeParserInterface::class, NamespaceNodeParser::class);
        $container->set(PhpFileNodeParserInterface::class, PhpFileNodeParser::class);

        return $container;
    }
}
