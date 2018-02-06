<?php

namespace UnitTests\PhpUnitGen\Console;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\AbstractConsoleConfigFactory;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Configuration\JsonConsoleConfigFactory;
use PhpUnitGen\Configuration\PhpConsoleConfigFactory;
use PhpUnitGen\Configuration\YamlConsoleConfigFactory;
use PhpUnitGen\Console\GenerateCommand;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class GenerateCommandTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers \PhpUnitGen\Console\GenerateCommand
 */
class GenerateCommandTest extends TestCase
{
    /**
     * @var ConsoleContainerFactoryInterface|MockObject $containerFactory
     */
    private $containerFactory;

    /**
     * @var Stopwatch|MockObject $stopwatch
     */
    private $stopwatch;

    /**
     * @var GenerateCommand|MockObject $command
     */
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->containerFactory = $this->createMock(ConsoleContainerFactoryInterface::class);
        $this->stopwatch        = $this->createMock(Stopwatch::class);

        $this->command = $this->getMockBuilder(GenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getConfiguration', 'getStyledIO'])
            ->getMock();
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::configure()
     */
    public function testConstruct(): void
    {
        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $containerFactoryProperty = (new \ReflectionClass(GenerateCommand::class))
            ->getProperty('containerFactory');
        $containerFactoryProperty->setAccessible(true);
        $stopwatchProperty = (new \ReflectionClass(GenerateCommand::class))
            ->getProperty('stopwatch');
        $stopwatchProperty->setAccessible(true);

        $this->assertEquals($this->containerFactory, $containerFactoryProperty->getValue($command));
        $this->assertEquals($this->stopwatch, $stopwatchProperty->getValue($command));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::configure()
     */
    public function testConfigure(): void
    {
        $this->assertEquals('generate', $this->command->getName());
        $this->assertEquals(['gen'], $this->command->getAliases());
        $this->assertEquals('Generate unit tests skeletons.', $this->command->getDescription());
        $this->assertEquals('Use it to generate your unit tests skeletons. See documentation on ' .
            'https://github.com/paul-thebaud/phpunit-generator/blob/master/DOCUMENTATION.md', $this->command->getHelp());

        $configOption = $this->command->getDefinition()->getOption('config');
        $this->assertEquals('c', $configOption->getShortcut());
        $this->assertTrue($configOption->isValueRequired());
        $this->assertEquals('The configuration file path.', $configOption->getDescription());
        $this->assertEquals('phpunitgen.yml', $configOption->getDefault());

        $fileOption = $this->command->getDefinition()->getOption('file');
        $this->assertEquals('f', $fileOption->getShortcut());
        $this->assertFalse($fileOption->acceptValue());
        $this->assertEquals('If you want file parsing.', $fileOption->getDescription());

        $dirOption = $this->command->getDefinition()->getOption('dir');
        $this->assertEquals('d', $dirOption->getShortcut());
        $this->assertFalse($dirOption->acceptValue());
        $this->assertEquals('If you want directory parsing.', $dirOption->getDescription());

        $defaultOption = $this->command->getDefinition()->getOption('default');
        $this->assertEquals('D', $defaultOption->getShortcut());
        $this->assertFalse($defaultOption->acceptValue());
        $this->assertEquals('If you want to use the default configuration.', $defaultOption->getDescription());

        $sourceParameter = $this->command->getDefinition()->getArgument('source');
        $this->assertFalse($sourceParameter->isRequired());
        $this->assertEquals('The source path (directory if "dir" option set).', $sourceParameter->getDescription());

        $targetParameter = $this->command->getDefinition()->getArgument('target');
        $this->assertFalse($targetParameter->isRequired());
        $this->assertEquals('The target path (directory if "dir" option set).', $targetParameter->getDescription());
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::execute()
     */
    public function testExecute(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = new NullOutput();
        $styledIO = $this->createMock(SymfonyStyle::class);
        $config = $this->createMock(ConsoleConfigInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $executor = $this->createMock(ConsoleExecutorInterface::class);

        $executeMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('execute');
        $executeMethod->setAccessible(true);

        $this->command->expects($this->once())->method('getConfiguration')
            ->with($input)->willReturn($config);
        $this->command->expects($this->once())->method('getStyledIO')
            ->with($input, $output)->willReturn($styledIO);

        $this->containerFactory->expects($this->once())->method('invoke')
            ->with($config, $this->isInstanceOf(SymfonyStyle::class), $this->stopwatch)->willReturn($container);

        $container->expects($this->once())->method('get')
            ->with(ConsoleExecutorInterface::class)->willReturn($executor);

        $executor->expects($this->once())->method('invoke')
            ->with();

        $styledIO->expects($this->never())->method('error');

        $this->assertEquals(0, $executeMethod->invoke($this->command, $input, $output));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::execute()
     */
    public function testExecuteThrowException(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = new NullOutput();
        $styledIO = $this->createMock(SymfonyStyle::class);
        $config = $this->createMock(ConsoleConfigInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $executor = $this->createMock(ConsoleExecutorInterface::class);

        $executeMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('execute');
        $executeMethod->setAccessible(true);

        $this->command->expects($this->once())->method('getConfiguration')
            ->with($input)->willReturn($config);
        $this->command->expects($this->once())->method('getStyledIO')
            ->with($input, $output)->willReturn($styledIO);

        $this->containerFactory->expects($this->once())->method('invoke')
            ->with($config, $this->isInstanceOf(SymfonyStyle::class), $this->stopwatch)->willReturn($container);

        $container->expects($this->once())->method('get')
            ->with(ConsoleExecutorInterface::class)->willReturn($executor);

        $executor->expects($this->once())->method('invoke')
            ->with()->willThrowException(new Exception('Exception in executor'));

        $styledIO->expects($this->once())->method('error')
            ->with('Exception in executor');

        $this->assertEquals(-1, $executeMethod->invoke($this->command, $input, $output));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getStyledIO()
     */
    public function testGetStyledIO(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = new NullOutput();

        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $getStyledIOMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getStyledIO');
        $getStyledIOMethod->setAccessible(true);

        $this->assertInstanceOf(SymfonyStyle::class, $getStyledIOMethod->invoke($command, $input, $output));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfiguration()
     */
    public function testGetConfigurationDefaultAndDir(): void
    {
        $input = $this->createMock(InputInterface::class);

        $command = $this->getMockBuilder(GenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getConfigurationFactory', 'validatePathsExist'])
            ->getMock();

        $command->expects($this->once())->method('validatePathsExist')
            ->with($input);

        $input->expects($this->exactly(2))->method('getOption')
            ->withConsecutive(['default'], ['dir'])->willReturnOnConsecutiveCalls(true, true);
        $input->expects($this->exactly(2))->method('getArgument')
            ->withConsecutive(['source'], ['target'])->willReturnOnConsecutiveCalls('source', 'target');

        $getConfigurationMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getConfiguration');
        $getConfigurationMethod->setAccessible(true);

        /** @var ConsoleConfigInterface $config */
        $config = $getConfigurationMethod->invoke($command, $input);

        $this->assertEmpty($config->getFiles());
        $this->assertEquals(['source' => 'target'], $config->getDirectories());
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfiguration()
     */
    public function testGetConfigurationDefaultAndFile(): void
    {
        $input = $this->createMock(InputInterface::class);

        $command = $this->getMockBuilder(GenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getConfigurationFactory', 'validatePathsExist'])
            ->getMock();

        $command->expects($this->once())->method('validatePathsExist')
            ->with($input);

        $input->expects($this->exactly(2))->method('getOption')
            ->withConsecutive(['default'], ['dir'])->willReturnOnConsecutiveCalls(true, false);
        $input->expects($this->exactly(2))->method('getArgument')
            ->withConsecutive(['source'], ['target'])->willReturnOnConsecutiveCalls('source', 'target');

        $getConfigurationMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getConfiguration');
        $getConfigurationMethod->setAccessible(true);

        /** @var ConsoleConfigInterface $config */
        $config = $getConfigurationMethod->invoke($command, $input);

        $this->assertEmpty($config->getDirectories());
        $this->assertEquals(['source' => 'target'], $config->getFiles());
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfiguration()
     */
    public function testGetConfigurationDir(): void
    {
        $input = $this->createMock(InputInterface::class);
        $configFactory = $this->createMock(AbstractConsoleConfigFactory::class);
        $config = $this->createMock(ConsoleConfigInterface::class);

        $command = $this->getMockBuilder(GenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getConfigurationFactory', 'validatePathsExist'])
            ->getMock();

        $command->expects($this->once())->method('validatePathsExist')
            ->with($input);
        $command->expects($this->once())->method('getConfigurationFactory')
            ->with('config.php')->willReturn($configFactory);

        $input->expects($this->exactly(3))->method('getOption')
            ->withConsecutive(['default'], ['config'], ['dir'])
            ->willReturnOnConsecutiveCalls(false, 'config.php', true);
        $input->expects($this->exactly(2))->method('getArgument')
            ->withConsecutive(['source'], ['target'])->willReturnOnConsecutiveCalls('source', 'target');

        $configFactory->expects($this->once())->method('invokeDir')
            ->with('config.php', 'source', 'target')->willReturn($config);

        $getConfigurationMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getConfiguration');
        $getConfigurationMethod->setAccessible(true);

        $this->assertEquals($config, $getConfigurationMethod->invoke($command, $input));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfiguration()
     */
    public function testGetConfigurationFile(): void
    {
        $input = $this->createMock(InputInterface::class);
        $configFactory = $this->createMock(AbstractConsoleConfigFactory::class);
        $config = $this->createMock(ConsoleConfigInterface::class);

        $command = $this->getMockBuilder(GenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getConfigurationFactory', 'validatePathsExist'])
            ->getMock();

        $command->expects($this->once())->method('validatePathsExist')
            ->with($input);
        $command->expects($this->once())->method('getConfigurationFactory')
            ->with('config.php')->willReturn($configFactory);

        $input->expects($this->exactly(4))->method('getOption')
            ->withConsecutive(['default'], ['config'], ['dir'], ['file'])
            ->willReturnOnConsecutiveCalls(false, 'config.php', false, true);
        $input->expects($this->exactly(2))->method('getArgument')
            ->withConsecutive(['source'], ['target'])->willReturnOnConsecutiveCalls('source', 'target');

        $configFactory->expects($this->once())->method('invokeFile')
            ->with('config.php', 'source', 'target')->willReturn($config);

        $getConfigurationMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getConfiguration');
        $getConfigurationMethod->setAccessible(true);

        $this->assertEquals($config, $getConfigurationMethod->invoke($command, $input));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfiguration()
     */
    public function testGetConfiguration(): void
    {
        $input = $this->createMock(InputInterface::class);
        $configFactory = $this->createMock(AbstractConsoleConfigFactory::class);
        $config = $this->createMock(ConsoleConfigInterface::class);

        $command = $this->getMockBuilder(GenerateCommand::class)
            ->setConstructorArgs([$this->containerFactory, $this->stopwatch])
            ->setMethods(['getConfigurationFactory', 'validatePathsExist'])
            ->getMock();

        $command->expects($this->never())->method('validatePathsExist')
            ->with($input);
        $command->expects($this->once())->method('getConfigurationFactory')
            ->with('config.php')->willReturn($configFactory);

        $input->expects($this->exactly(4))->method('getOption')
            ->withConsecutive(['default'], ['config'], ['dir'], ['file'])
            ->willReturnOnConsecutiveCalls(false, 'config.php', false, false);
        $input->expects($this->never(2))->method('getArgument');

        $configFactory->expects($this->once())->method('invoke')
            ->with('config.php')->willReturn($config);

        $getConfigurationMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getConfiguration');
        $getConfigurationMethod->setAccessible(true);

        $this->assertEquals($config, $getConfigurationMethod->invoke($command, $input));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfigurationFactory()
     */
    public function testGetConfigurationFactoryWithValidPath(): void
    {
        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $getConfigurationFactoryMethod = (new \ReflectionClass(GenerateCommand::class))->getMethod('getConfigurationFactory');
        $getConfigurationFactoryMethod->setAccessible(true);

        $this->assertInstanceOf(YamlConsoleConfigFactory::class, $getConfigurationFactoryMethod
            ->invoke($command, __DIR__ . '/../../../examples/phpunitgen.config.yml'));

        $this->assertInstanceOf(PhpConsoleConfigFactory::class, $getConfigurationFactoryMethod
            ->invoke($command, __DIR__ . '/../../../examples/phpunitgen.config.php'));

        $this->assertInstanceOf(JsonConsoleConfigFactory::class, $getConfigurationFactoryMethod
            ->invoke($command, __DIR__ . '/../../../examples/phpunitgen.config.json'));
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfigurationFactory()
     */
    public function testGetConfigurationFactoryWithInvalidPath(): void
    {
        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $getConfigurationFactoryMethod = (new \ReflectionClass(GenerateCommand::class))
            ->getMethod('getConfigurationFactory');
        $getConfigurationFactoryMethod->setAccessible(true);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Config file "invalid_config_path" does not exists');

        $getConfigurationFactoryMethod->invoke($command, 'invalid_config_path');
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::getConfigurationFactory()
     */
    public function testGetConfigurationFactoryWithInvalidExtension(): void
    {
        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $getConfigurationFactoryMethod = (new \ReflectionClass(GenerateCommand::class))
            ->getMethod('getConfigurationFactory');
        $getConfigurationFactoryMethod->setAccessible(true);

        $path = realpath(__DIR__ . '/../resource/invalid_config.txt');

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage(sprintf('Config file "%s" must have .yml, .json or .php extension', $path));

        $getConfigurationFactoryMethod->invoke($command, $path);
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::validatePathsExist()
     */
    public function testValidatePathsExistMissingSource(): void
    {
        $input = $this->createMock(InputInterface::class);

        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $validatePathsExistMethod = (new \ReflectionClass(GenerateCommand::class))
            ->getMethod('validatePathsExist');
        $validatePathsExistMethod->setAccessible(true);

        $input->expects($this->once())->method('getArgument')
            ->with('source')->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing the source path');

        $validatePathsExistMethod->invoke($command, $input);
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::validatePathsExist()
     */
    public function testValidatePathsExistMissingTarget(): void
    {
        $input = $this->createMock(InputInterface::class);

        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $validatePathsExistMethod = (new \ReflectionClass(GenerateCommand::class))
            ->getMethod('validatePathsExist');
        $validatePathsExistMethod->setAccessible(true);

        $input->expects($this->exactly(2))->method('getArgument')
            ->withConsecutive(['source'], ['target'])->willReturnOnConsecutiveCalls('source', null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing the target path');

        $validatePathsExistMethod->invoke($command, $input);
    }

    /**
     * @covers \PhpUnitGen\Console\GenerateCommand::validatePathsExist()
     */
    public function testValidatePathsExistNoExceptionThrown(): void
    {
        $input = $this->createMock(InputInterface::class);

        $command = new GenerateCommand($this->containerFactory, $this->stopwatch);

        $validatePathsExistMethod = (new \ReflectionClass(GenerateCommand::class))
            ->getMethod('validatePathsExist');
        $validatePathsExistMethod->setAccessible(true);

        $input->expects($this->exactly(2))->method('getArgument')
            ->withConsecutive(['source'], ['target'])->willReturnOnConsecutiveCalls('source', 'target');

        $validatePathsExistMethod->invoke($command, $input);
    }
}
