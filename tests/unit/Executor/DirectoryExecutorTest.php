<?php

namespace UnitTests\PhpUnitGen\Executor;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\Handler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Exception\FileNotFoundException;
use PhpUnitGen\Executor\DirectoryExecutor;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class DirectoryExecutorTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Executor\DirectoryExecutor
 */
class DirectoryExecutorTest extends TestCase
{
    /**
     * @var ConsoleConfigInterface|MockObject $config The configuration to use.
     */
    private $config;

    /**
     * @var StyleInterface|MockObject $output The output to display message.
     */
    private $output;

    /**
     * @var FileExecutorInterface|MockObject $fileExecutor The file executor to parse files.
     */
    private $fileExecutor;

    /**
     * @var FilesystemInterface|MockObject $fileSystem The file system to use.
     */
    private $fileSystem;

    /**
     * @var ExceptionCatcherInterface|MockObject $exceptionCatcher An exception catcher to catch exception.
     */
    private $exceptionCatcher;

    /**
     * @var ReportInterface|MockObject $report The report to use.
     */
    private $report;

    /**
     * @var DirectoryExecutor
     */
    private $directoryExecutor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->config           = $this->createMock(ConsoleConfigInterface::class);
        $this->output           = $this->createMock(StyleInterface::class);
        $this->fileExecutor     = $this->createMock(FileExecutorInterface::class);
        $this->fileSystem       = $this->createMock(FilesystemInterface::class);
        $this->exceptionCatcher = $this->createMock(ExceptionCatcherInterface::class);
        $this->report           = $this->createMock(ReportInterface::class);

        $this->directoryExecutor = new DirectoryExecutor(
            $this->config,
            $this->output,
            $this->fileExecutor,
            $this->fileSystem,
            $this->exceptionCatcher,
            $this->report
        );

        $this->output->expects($this->once())->method('section')
            ->with('Directory "source/directory" parsing begins.');
    }

    /**
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::invoke()
     */
    public function testDirectoryDoesNotExists(): void
    {
        $this->fileSystem->expects($this->once())->method('has')
            ->with('source/directory')->willReturn(false);

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('The source directory "source/directory" does not exist');

        $this->directoryExecutor->invoke('source/directory', 'target/directory');
    }

    /**
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::invoke()
     */
    public function testPathIsAFile(): void
    {
        $handler = $this->createMock(Handler::class);
        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('file');

        $this->fileSystem->expects($this->once())->method('has')
            ->with('source/directory')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('source/directory')->willReturn($handler);

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('The source path "source/directory" is not a directory');

        $this->directoryExecutor->invoke('source/directory', 'target/directory');
    }

    /**
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::invoke()
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::executeFileExecutor()
     */
    public function testExecuteOnFileThrowException(): void
    {
        $handler = $this->createMock(Handler::class);
        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('dir');

        $this->fileSystem->expects($this->once())->method('has')
            ->with('source/directory')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('source/directory')->willReturn($handler);
        $this->fileSystem->expects($this->once())->method('listContents')
            ->with('source/directory', true)->willReturn([
                ['path' => 'source/directory/Package/File.php']
            ]);

        $exception = new Exception('Invalid php code');

        $this->fileExecutor->expects($this->once())->method('invoke')
            ->with('source/directory/Package/File.php', 'target/directory/Package/FileTest.php', 'FileTest')
            ->willThrowException($exception);

        $this->exceptionCatcher->expects($this->once())->method('catch')
            ->with($exception);

        $this->directoryExecutor->invoke('source/directory', 'target/directory');
    }

    /**
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::invoke()
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::executeFileExecutor()
     */
    public function testExecuteOnFileWithSlashes(): void
    {
        $handler = $this->createMock(Handler::class);
        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('dir');

        $this->fileSystem->expects($this->once())->method('has')
            ->with('source/directory')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('source/directory')->willReturn($handler);
        $this->fileSystem->expects($this->once())->method('listContents')
            ->with('source/directory', true)->willReturn([
                ['path' => 'source/directory/Package/File.php']
            ]);

        $this->fileExecutor->expects($this->once())->method('invoke')
            ->with('source/directory/Package/File.php', 'target/directory/Package/FileTest.php', 'FileTest');

        $this->exceptionCatcher->expects($this->never())->method('catch');

        $this->directoryExecutor->invoke('source/directory/', 'target/directory/');
    }

    /**
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::invoke()
     * @covers \PhpUnitGen\Executor\DirectoryExecutor::executeFileExecutor()
     */
    public function testExecuteOnFileWithNotPhpExtension(): void
    {
        $handler = $this->createMock(Handler::class);
        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('dir');

        $this->fileSystem->expects($this->once())->method('has')
            ->with('source/directory')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('source/directory')->willReturn($handler);
        $this->fileSystem->expects($this->once())->method('listContents')
            ->with('source/directory', true)->willReturn([
                ['path' => 'source/directory/Package/File.phtml']
            ]);

        $this->fileExecutor->expects($this->once())->method('invoke')
            ->with('source/directory/Package/File.phtml', 'target/directory/Package/FileTest.php', 'FileTest');

        $this->exceptionCatcher->expects($this->never())->method('catch');

        $this->directoryExecutor->invoke('source/directory/', 'target/directory/');
    }
}
