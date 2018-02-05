<?php

namespace UnitTests\PhpUnitGen\Console;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Console\AbstractGenerateCommand;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class AbstractGenerateCommandTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Console\AbstractGenerateCommand
 */
class AbstractGenerateCommandTest extends TestCase
{
    /**
     * @var ConsoleContainerFactoryInterface $containerFactory
     */
    private $containerFactory;

    /**
     * @var Stopwatch $stopwatch
     */
    private $stopwatch;

    /**
     * @var AbstractGenerateCommand $command
     */
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->containerFactory = $this->createMock(ConsoleContainerFactoryInterface::class);
        $this->stopwatch        = $this->createMock(Stopwatch::class);

        $this->command = $this->getMockBuilder(AbstractGenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getSymfonyStyle', 'getConfiguration'])
            ->getMockForAbstractClass();
    }

    /**
     * @covers \PhpUnitGen\Console\AbstractGenerateCommand::__construct()
     */
    public function testConstruct(): void
    {
        $reflection               = new \ReflectionClass(AbstractGenerateCommand::class);
        $containerFactoryProperty = $reflection->getProperty('containerFactory');
        $containerFactoryProperty->setAccessible(true);
        $stopwatchProperty = $reflection->getProperty('stopwatch');
        $stopwatchProperty->setAccessible(true);


        $this->assertEquals($this->containerFactory, $containerFactoryProperty->getValue($this->command));
        $this->assertEquals($this->stopwatch, $stopwatchProperty->getValue($this->command));
    }

    /**
     * @covers \PhpUnitGen\Console\AbstractGenerateCommand::execute()
     */
    public function testExecute(): void
    {
        $input        = $this->createMock(InputInterface::class);
        $output       = new NullOutput();
        $symfonyStyle = $this->createMock(SymfonyStyle::class);
        $config       = $this->createMock(ConsoleConfigInterface::class);
        $container    = $this->createMock(ContainerInterface::class);
        $executor     = $this->createMock(ConsoleExecutorInterface::class);

        $this->stopwatch->expects($this->once())
            ->method('start')->with('command');

        $this->command->expects($this->once())
            ->method('getSymfonyStyle')->with($input, $output)->willReturn($symfonyStyle);
        $this->command->expects($this->once())
            ->method('getConfiguration')->with($input)->willReturn($config);

        $this->containerFactory->expects($this->once())
            ->method('invoke')->with($config, $this->callback(function ($style) use ($symfonyStyle) {
                return $style === $symfonyStyle;
            }), $this->stopwatch)
            ->willReturn($container);

        $container->expects($this->once())
            ->method('get')->with(ConsoleExecutorInterface::class)->willReturn($executor);

        $this->assertEquals(0, $this->command->run($input, $output));
    }

    /**
     * @covers \PhpUnitGen\Console\AbstractGenerateCommand::execute()
     */
    public function testExecuteThrowException(): void
    {
        $input        = $this->createMock(InputInterface::class);
        $output       = new NullOutput();
        $symfonyStyle = $this->createMock(SymfonyStyle::class);

        $this->stopwatch->expects($this->once())
            ->method('start')->with('command');

        $this->command->expects($this->once())
            ->method('getSymfonyStyle')->with($input, $output)->willReturn($symfonyStyle);
        $this->command->expects($this->once())
            ->method('getConfiguration')->with($input)
            ->willThrowException(new InvalidConfigException('Invalid configuration'));

        $symfonyStyle->expects($this->once())
            ->method('error')->with('Invalid configuration');

        $this->assertEquals(-1, $this->command->run($input, $output));
    }
}
