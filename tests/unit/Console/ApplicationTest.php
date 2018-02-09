<?php

namespace UnitTests\PhpUnitGen\Console;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Console\Application;
use PhpUnitGen\Console\GenerateCommand;
use PhpUnitGen\Container\ConsoleContainerFactory;
use Symfony\Component\Console\Application as AbstractApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class ApplicationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Console\Application
 */
class ApplicationTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Console\Application::__construct()
     */
    public function testConstruct(): void
    {
        $reflection = new \ReflectionClass(GenerateCommand::class);

        $containerFactoryProperty = $reflection->getProperty('containerFactory');
        $containerFactoryProperty->setAccessible(true);

        $stopwatchProperty = $reflection->getProperty('stopwatch');
        $stopwatchProperty->setAccessible(true);

        $defaultCommandProperty = (new \ReflectionClass(AbstractApplication::class))
            ->getProperty('defaultCommand');
        $defaultCommandProperty->setAccessible(true);

        $app = new Application();

        $this->assertInstanceOf(AbstractApplication::class, $app);

        $this->assertSame('phpunitgen', $app->getName());
        $this->assertSame('2.0.0', $app->getVersion());

        $generate = $app->get('generate');
        $this->assertInstanceOf(GenerateCommand::class, $generate);
        $this->assertInstanceOf(ConsoleContainerFactory::class, $containerFactoryProperty
            ->getValue($generate));
        $this->assertInstanceOf(Stopwatch::class, $stopwatchProperty
            ->getValue($generate));
        $this->assertSame('generate', $defaultCommandProperty->getValue($app));
    }

    /**
     * @covers \PhpUnitGen\Console\Application::doRun()
     */
    public function testDoRunQuiet(): void
    {
        $app = $this->getMockBuilder(Application::class)
            ->setMethods(['doRunParent'])
            ->getMock();

        $input  = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $output->expects($this->once())->method('isQuiet')
            ->willReturn(true);
        $output->expects($this->never())->method('writeln');

        $app->expects($this->once())->method('doRunParent')
            ->with($input, $output);

        $this->assertSame(0, $app->doRun($input, $output));
    }

    /**
     * @covers \PhpUnitGen\Console\Application::doRun()
     */
    public function testDoRunNotQuiet(): void
    {
        $app = $this->getMockBuilder(Application::class)
            ->setMethods(['doRunParent'])
            ->getMock();

        $input  = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $output->expects($this->once())->method('isQuiet')
            ->willReturn(false);
        $output->expects($this->once())->method('writeln')
            ->with("PhpUnitGen by Paul Thébaud (version <info>2.0.0</info>).\n");

        $app->expects($this->once())->method('doRunParent')
            ->with($input, $output)->willReturn(0);

        $this->assertSame(0, $app->doRun($input, $output));
    }

    /**
     * @covers \PhpUnitGen\Console\Application::doRunParent()
     */
    public function testDoRunParent(): void
    {
        $input  = $this->createMock(InputInterface::class);
        $input->expects($this->once())->method('hasParameterOption')
            ->with(array('--version', '-V'), true)->willReturn(true);
        $output = new NullOutput();

        $app = new Application();

        $doRunParentMethod = (new \ReflectionClass($app))
            ->getMethod('doRunParent');
        $doRunParentMethod->setAccessible(true);

        $this->assertSame(0, $doRunParentMethod->invoke($app, $input, $output));
    }
}
