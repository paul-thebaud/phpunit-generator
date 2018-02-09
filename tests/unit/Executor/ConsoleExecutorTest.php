<?php

namespace UnitTests\PhpUnitGen\Executor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Executor\ConsoleExecutor;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Class ConsoleExecutorTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Executor\ConsoleExecutor
 */
class ConsoleExecutorTest extends TestCase
{
    /**
     * @var ConsoleConfigInterface|MockObject $config
     */
    private $config;

    /**
     * @var StyleInterface|MockObject $output
     */
    private $output;

    /**
     * @var Stopwatch|MockObject $stopwatch
     */
    private $stopwatch;

    /**
     * @var DirectoryExecutorInterface|MockObject $directoryExecutor
     */
    private $directoryExecutor;

    /**
     * @var FileExecutorInterface|MockObject $fileExecutor
     */
    private $fileExecutor;

    /**
     * @var ExceptionCatcherInterface|MockObject $exceptionCatcher
     */
    private $exceptionCatcher;

    /**
     * @var ReportInterface|MockObject $report
     */
    private $report;

    /**
     * @var ConsoleExecutor $consoleExecutor
     */
    private $consoleExecutor;

    /**
     * @covers \PhpUnitGen\Executor\ConsoleExecutor
     */
    protected function setUp(): void
    {
        $this->config            = $this->createMock(ConsoleConfigInterface::class);
        $this->output            = $this->createMock(StyleInterface::class);
        $this->stopwatch         = $this->createMock(Stopwatch::class);
        $this->directoryExecutor = $this->createMock(DirectoryExecutorInterface::class);
        $this->fileExecutor      = $this->createMock(FileExecutorInterface::class);
        $this->exceptionCatcher  = $this->createMock(ExceptionCatcherInterface::class);
        $this->report            = $this->createMock(ReportInterface::class);

        $this->consoleExecutor = new ConsoleExecutor(
            $this->config,
            $this->output,
            $this->stopwatch,
            $this->directoryExecutor,
            $this->fileExecutor,
            $this->exceptionCatcher,
            $this->report
        );

        $event = $this->createMock(StopwatchEvent::class);

        $this->stopwatch->expects($this->once())->method('stop')
            ->with('command')->willReturn($event);

        $event->expects($this->once())->method('getDuration')
            ->with()->willReturn(500);
        $event->expects($this->once())->method('getMemory')
            ->with()->willReturn(100000);

        $this->report->expects($this->once())->method('getParsedFileNumber')
            ->with()->willReturn(10);
        $this->report->expects($this->once())->method('getParsedFileFromDirectoryNumber')
            ->with()->willReturn(5);
        $this->report->expects($this->once())->method('getParsedDirectoryNumber')
            ->with()->willReturn(2);

        $this->output->expects($this->exactly(4))->method('text')
            ->withConsecutive(
                ['<options=bold,underscore>Duration:</> 500 milliseconds'],
                ['<options=bold,underscore>Memory usage:</> 100000 bytes'],
                ['<options=bold,underscore>Parsed files number:</> 15 files'],
                ['<options=bold,underscore>Parsed directories number:</> 2 directories']
            );
    }

    /**
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::invoke()
     */
    public function testWithoutFilesOrDirectories(): void
    {
        $this->output->expects($this->once())->method('section')
            ->with('PhpUnitGen finished all tasks.');
        $this->directoryExecutor->expects($this->never())->method('invoke');
        $this->fileExecutor->expects($this->never())->method('invoke');

        $this->config->expects($this->once())->method('getDirectories')
            ->with()->willReturn([]);
        $this->config->expects($this->once())->method('getFiles')
            ->with()->willReturn([]);

        $this->consoleExecutor->invoke();
    }

    /**
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::invoke()
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::executeOnDirectories()
     */
    public function testWithDirectories(): void
    {
        $this->output->expects($this->exactly(2))->method('section')
            ->withConsecutive(['Directories parsing begins.'], ['PhpUnitGen finished all tasks.']);
        $this->directoryExecutor->expects($this->exactly(2))->method('invoke')
            ->withConsecutive(
                ['dir1_source', 'dir1_target'],
                ['dir2_source', 'dir2_target']
            );
        $this->fileExecutor->expects($this->never())->method('invoke');

        $this->config->expects($this->exactly(2))->method('getDirectories')
            ->with()->willReturn([
                'dir1_source' => 'dir1_target',
                'dir2_source' => 'dir2_target'
            ]);
        $this->config->expects($this->once())->method('getFiles')
            ->with()->willReturn([]);

        $this->report->expects($this->exactly(2))->method('increaseParsedDirectoryNumber')
            ->with();

        $this->consoleExecutor->invoke();
    }

    /**
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::invoke()
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::executeOnDirectories()
     */
    public function testWithDirectoriesThrowException(): void
    {
        $exception = new Exception('Invalid dir');

        $this->output->expects($this->exactly(2))->method('section')
            ->withConsecutive(['Directories parsing begins.'], ['PhpUnitGen finished all tasks.']);
        $this->directoryExecutor->expects($this->once())->method('invoke')
            ->with('dir1_source', 'dir1_target')->willThrowException($exception);
        $this->fileExecutor->expects($this->never())->method('invoke');

        $this->config->expects($this->exactly(2))->method('getDirectories')
            ->with()->willReturn([
                'dir1_source' => 'dir1_target'
            ]);
        $this->config->expects($this->once())->method('getFiles')
            ->with()->willReturn([]);

        $this->report->expects($this->never())->method('increaseParsedDirectoryNumber');

        $this->exceptionCatcher->expects($this->once())->method('catch')
            ->with($exception);

        $this->consoleExecutor->invoke();
    }

    /**
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::invoke()
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::executeOnFiles()
     */
    public function testWithFiles(): void
    {
        $this->output->expects($this->exactly(2))->method('section')
            ->withConsecutive(['Global files parsing begins.'], ['PhpUnitGen finished all tasks.']);
        $this->fileExecutor->expects($this->exactly(2))->method('invoke')
            ->withConsecutive(
                ['file1_source', 'file1_target'],
                ['file2_source', 'file2_target']
            );
        $this->directoryExecutor->expects($this->never())->method('invoke');

        $this->config->expects($this->exactly(2))->method('getFiles')
            ->with()->willReturn([
                'file1_source' => 'file1_target',
                'file2_source' => 'file2_target'
            ]);
        $this->config->expects($this->once())->method('getDirectories')
            ->with()->willReturn([]);

        $this->report->expects($this->exactly(2))->method('increaseParsedFileNumber')
            ->with();

        $this->consoleExecutor->invoke();
    }

    /**
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::invoke()
     * @covers \PhpUnitGen\Executor\ConsoleExecutor::executeOnFiles()
     */
    public function testWithFilesThrowException(): void
    {
        $exception = new Exception('Invalid dir');

        $this->output->expects($this->exactly(2))->method('section')
            ->withConsecutive(['Global files parsing begins.'], ['PhpUnitGen finished all tasks.']);
        $this->fileExecutor->expects($this->once())->method('invoke')
            ->with('file1_source', 'file1_target')->willThrowException($exception);
        $this->directoryExecutor->expects($this->never())->method('invoke');

        $this->config->expects($this->exactly(2))->method('getFiles')
            ->with()->willReturn([
                'file1_source' => 'file1_target'
            ]);
        $this->config->expects($this->once())->method('getDirectories')
            ->with()->willReturn([]);

        $this->report->expects($this->never())->method('increaseParsedFileNumber');

        $this->exceptionCatcher->expects($this->once())->method('catch')
            ->with($exception);

        $this->consoleExecutor->invoke();
    }
}