<?php

namespace UnitTests\PhpUnitGen\Container;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\ConsoleContainerFactory;
use PhpUnitGen\Container\Container;
use PhpUnitGen\Container\ContainerFactory;
use PhpUnitGen\Exception\ExceptionCatcher;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Executor\ConsoleExecutor;
use PhpUnitGen\Executor\DirectoryExecutor;
use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Executor\FileExecutor;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Parser\PhpParser;
use PhpUnitGen\Renderer\PhpFileRenderer;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;
use PhpUnitGen\Report\Report;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use PhpUnitGen\Validator\FileValidator;
use PhpUnitGen\Validator\ValidatorInterface\FileValidatorInterface;
use Slim\Views\PhpRenderer;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class ConsoleContainerFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Container\ConsoleContainerFactory
 */
class ConsoleContainerFactoryTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Container\ConsoleContainerFactory::invoke()
     */
    public function testInvokeFactory(): void
    {
        $config = $this->createMock(ConsoleConfigInterface::class);
        $config->expects($this->once())->method('getTemplatesPath')
            ->with()->willReturn('templates/path/');
        $styledIO  = $this->createMock(StyleInterface::class);
        $stopwatch = $this->createMock(Stopwatch::class);

        $factory = new ConsoleContainerFactory(new ContainerFactory());

        $container = $factory->invoke($config, $styledIO, $stopwatch);

        $this->assertInstanceOf(Container::class, $container);

        // Check ContainerFactory invoke is called
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

        // Check additional dependencies are added
        $this->assertEquals($styledIO, $container->get(StyleInterface::class));
        $this->assertEquals($stopwatch, $container->get(Stopwatch::class));

        $fileSystem = $container->get(FilesystemInterface::class);
        $this->assertInstanceOf(Filesystem::class, $fileSystem);
        $this->assertInstanceOf(Local::class, $fileSystem->getAdapter());

        $this->assertInstanceOf(ExceptionCatcher::class, $container->get(ExceptionCatcherInterface::class));
        $this->assertInstanceOf(FileValidator::class, $container->get(FileValidatorInterface::class));
        $this->assertInstanceOf(FileExecutor::class, $container->get(FileExecutorInterface::class));
        $this->assertInstanceOf(DirectoryExecutor::class, $container->get(DirectoryExecutorInterface::class));
        $this->assertInstanceOf(ConsoleExecutor::class, $container->get(ConsoleExecutorInterface::class));

    }
}
