<?php

namespace UnitTests\PhpUnitGen\Console;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Console\AbstractGenerateCommand;
use PhpUnitGen\Console\Application;
use PhpUnitGen\Console\GenerateCommand;
use PhpUnitGen\Console\GenerateDefaultCommand;
use PhpUnitGen\Console\GenerateOneCommand;
use PhpUnitGen\Console\GenerateOneDefaultCommand;
use PhpUnitGen\Container\ConsoleContainerFactory;
use Symfony\Component\Console\Application as AbstractApplication;
use Symfony\Component\Console\Input\InputInterface;
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
    private $containerFactoryProperty;
    private $stopwatchProperty;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $reflection                     = new \ReflectionClass(AbstractGenerateCommand::class);
        $this->containerFactoryProperty = $reflection->getProperty('containerFactory');
        $this->containerFactoryProperty->setAccessible(true);
        $this->stopwatchProperty = $reflection->getProperty('stopwatch');
        $this->stopwatchProperty->setAccessible(true);
    }

    /**
     * @covers \PhpUnitGen\Console\Application::__construct()
     */
    public function testConstruct(): void
    {
        $app = new Application();

        $this->assertInstanceOf(AbstractApplication::class, $app);

        $this->assertEquals('phpunitgen', $app->getName());
        $this->assertEquals('2.0.0', $app->getVersion());

        $generate = $app->get('gen');
        $this->assertInstanceOf(GenerateCommand::class, $generate);
        $this->assertInstanceOf(ConsoleContainerFactory::class, $this->containerFactoryProperty
            ->getValue($generate));
        $this->assertInstanceOf(Stopwatch::class, $this->stopwatchProperty
            ->getValue($generate));

        $generateDefault = $app->get('gen-def');
        $this->assertInstanceOf(GenerateDefaultCommand::class, $generateDefault);
        $this->assertInstanceOf(ConsoleContainerFactory::class, $this->containerFactoryProperty
            ->getValue($generateDefault));
        $this->assertInstanceOf(Stopwatch::class, $this->stopwatchProperty
            ->getValue($generateDefault));

        $generateOne = $app->get('gen-one');
        $this->assertInstanceOf(GenerateOneCommand::class, $generateOne);
        $this->assertInstanceOf(ConsoleContainerFactory::class, $this->containerFactoryProperty
            ->getValue($generateOne));
        $this->assertInstanceOf(Stopwatch::class, $this->stopwatchProperty
            ->getValue($generateOne));

        $generateOneDefault = $app->get('gen-one-def');
        $this->assertInstanceOf(GenerateOneDefaultCommand::class, $generateOneDefault);
        $this->assertInstanceOf(ConsoleContainerFactory::class, $this->containerFactoryProperty
            ->getValue($generateOneDefault));
        $this->assertInstanceOf(Stopwatch::class, $this->stopwatchProperty
            ->getValue($generateOneDefault));
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

        $this->assertEquals(0, $app->doRun($input, $output));
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
            ->with("PhpUnitGen by Paul Thébaud.\n");

        $app->expects($this->once())->method('doRunParent')
            ->with($input, $output)->willReturn(0);

        $this->assertEquals(0, $app->doRun($input, $output));
    }
}
