<?php

namespace UnitTests\PhpUnitGen\Container;

use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\Container;
use PhpUnitGen\Container\ContainerFactory;
use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Parser\PhpParser;
use PhpUnitGen\Renderer\PhpFileRenderer;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;
use PhpUnitGen\Report\Report;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use Slim\Views\PhpRenderer;

/**
 * Class ContainerFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Container\ContainerFactory
 */
class ContainerFactoryTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Container\ContainerFactory::invoke()
     */
    public function testInvokeFactory(): void
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->expects($this->once())->method('getTemplatesPath')
            ->with()->willReturn('templates/path/');

        $factory = new ContainerFactory();

        $container = $factory->invoke($config);

        $this->assertInstanceOf(Container::class, $container);

        $this->assertEquals($config, $container->get(ConfigInterface::class));
        $this->assertEquals($config, $container->get(ConsoleConfigInterface::class));

        $this->assertInstanceOf(Parser\Multiple::class, $container->get(Parser::class));

        $phpRenderer = $container->get(PhpRenderer::class);
        $this->assertInstanceOf(PhpRenderer::class, $phpRenderer);
        $this->assertEquals('templates/path/', $phpRenderer->getTemplatePath());

        $this->assertInstanceOf(PhpParser::class, $container->get(PhpParserInterface::class));
        $this->assertInstanceOf(Executor::class, $container->get(ExecutorInterface::class));
        $this->assertInstanceOf(Report::class, $container->get(ReportInterface::class));
        $this->assertInstanceOf(PhpFileRenderer::class, $container->get(PhpFileRendererInterface::class));
    }
}
