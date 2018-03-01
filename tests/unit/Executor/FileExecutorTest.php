<?php

namespace UnitTests\PhpUnitGen\Executor;

use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\FileExistsException;
use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Executor\FileExecutor;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use PhpUnitGen\Validator\ValidatorInterface\FileValidatorInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class FileExecutorTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Executor\FileExecutor
 */
class FileExecutorTest extends TestCase
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
     * @var ExecutorInterface|MockObject $executor
     */
    private $executor;

    /**
     * @var FilesystemInterface|MockObject $fileSystem
     */
    private $fileSystem;

    /**
     * @var FileValidatorInterface|MockObject $fileValidator
     */
    private $fileValidator;

    /**
     * @var ReportInterface|MockObject $report
     */
    private $report;

    /**
     * @var FileExecutor $fileExecutor
     */
    private $fileExecutor;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->config        = $this->createMock(ConsoleConfigInterface::class);
        $this->output        = $this->createMock(StyleInterface::class);
        $this->executor      = $this->createMock(ExecutorInterface::class);
        $this->fileSystem    = $this->createMock(FilesystemInterface::class);
        $this->fileValidator = $this->createMock(FileValidatorInterface::class);
        $this->report        = $this->createMock(ReportInterface::class);

        $this->fileExecutor = new FileExecutor(
            $this->config,
            $this->output,
            $this->executor,
            $this->fileSystem,
            $this->fileValidator,
            $this->report
        );
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     */
    public function testFileNotValidated(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')->willReturn(false);

        $this->assertFalse($this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest'));
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     */
    public function testFileNotReadable(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('source/file.php')->willReturn(false);

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The file "source/file.php" is not readable');

        $this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest');
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     */
    public function testNoGeneratedTest(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('source/file.php')->willReturn('<?php some php code');

        $this->executor->expects($this->once())->method('invoke')
            ->with('<?php some php code', 'FileTest')->willReturn(null);

        $this->output->expects($this->once())->method('note')
            ->with('Parsing file "source/file.php" completed: no testable functions in code');

        $this->assertFalse($this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest'));
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     * @covers \PhpUnitGen\Executor\FileExecutor::checkTargetPath()
     */
    public function testFileExistsAndNoOverwrite(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')
            ->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('source/file.php')->willReturn('<?php some php code');

        $this->executor->expects($this->once())->method('invoke')
            ->with('<?php some php code', 'FileTest')->willReturn('<?php generated tests skeleton');

        $this->fileSystem->expects($this->once())->method('has')
            ->with('target/file.php')->willReturn(true);

        $this->config->expects($this->once())->method('hasOverwrite')
            ->with()->willReturn(false);

        $this->expectException(FileExistsException::class);
        $this->expectExceptionMessage('The target file "target/file.php" already exists');

        $this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest');
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     * @covers \PhpUnitGen\Executor\FileExecutor::checkTargetPath()
     */
    public function testFileExistsAndBackupToExistingFile(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')
            ->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('source/file.php')->willReturn('<?php some php code');

        $this->executor->expects($this->once())->method('invoke')
            ->with('<?php some php code', 'FileTest')->willReturn('<?php generated tests skeleton');

        $this->fileSystem->expects($this->exactly(2))->method('has')
            ->withConsecutive(['target/file.php'], ['target/file.php.bak'])
            ->willReturn(true, true);

        $this->config->expects($this->once())->method('hasOverwrite')
            ->with()->willReturn(true);

        $this->config->expects($this->once())->method('hasBackup')
            ->with()->willReturn(true);

        $this->expectException(FileExistsException::class);
        $this->expectExceptionMessage('The backup target file "target/file.php.bak" already exists');

        $this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest');
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     * @covers \PhpUnitGen\Executor\FileExecutor::checkTargetPath()
     */
    public function testFileExistsAndBackupFile(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')
            ->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('source/file.php')->willReturn('<?php some php code');

        $this->executor->expects($this->once())->method('invoke')
            ->with('<?php some php code', 'FileTest')->willReturn('<?php generated tests skeleton');

        $this->fileSystem->expects($this->exactly(2))->method('has')
            ->withConsecutive(['target/file.php'], ['target/file.php.bak'])
            ->willReturn(true, false);

        $this->config->expects($this->once())->method('hasOverwrite')
            ->with()->willReturn(true);

        $this->config->expects($this->once())->method('hasBackup')
            ->with()->willReturn(true);

        $this->fileSystem->expects($this->once())->method('delete')
            ->with('target/file.php');
        $this->fileSystem->expects($this->once())->method('copy')
            ->with('target/file.php', 'target/file.php.bak');
        $this->fileSystem->expects($this->once())->method('write')
            ->with('target/file.php', '<?php generated tests skeleton');

        $this->output->expects($this->once())->method('text')
            ->with('Parsing file "source/file.php" completed');

        $this->report->expects($this->once())->method('increaseParsedFileNumber')
            ->with();

        $this->assertTrue($this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest'));
    }

    /**
     * @covers \PhpUnitGen\Executor\FileExecutor::invoke()
     * @covers \PhpUnitGen\Executor\FileExecutor::checkTargetPath()
     */
    public function testFileExistsAndOverwrite(): void
    {
        $this->fileValidator->expects($this->once())->method('validate')
            ->with('source/file.php')
            ->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('source/file.php')->willReturn('<?php some php code');

        $this->executor->expects($this->once())->method('invoke')
            ->with('<?php some php code', 'FileTest')->willReturn('<?php generated tests skeleton');

        $this->fileSystem->expects($this->once())->method('has')
            ->with('target/file.php')->willReturn(true);

        $this->config->expects($this->once())->method('hasOverwrite')
            ->with()->willReturn(true);

        $this->fileSystem->expects($this->once())->method('delete')
            ->with('target/file.php');
        $this->fileSystem->expects($this->once())->method('write')
            ->with('target/file.php', '<?php generated tests skeleton');

        $this->output->expects($this->once())->method('text')
            ->with('Parsing file "source/file.php" completed');

        $this->report->expects($this->once())->method('increaseParsedFileNumber')
            ->with();

        $this->assertTrue($this->fileExecutor->invoke('source/file.php', 'target/file.php', 'FileTest'));
    }
}
