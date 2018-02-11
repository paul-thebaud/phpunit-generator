<?php

namespace PhpUnitGen\Executor;

use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\FileExistsException;
use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use PhpUnitGen\Validator\ValidatorInterface\FileValidatorInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class FileExecutor.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FileExecutor implements FileExecutorInterface
{
    /**
     * @var ConsoleConfigInterface $config The configuration to use.
     */
    private $config;

    /**
     * @var StyleInterface $output The output to display message.
     */
    private $output;

    /**
     * @var ExecutorInterface $executor The executor for php code.
     */
    private $executor;

    /**
     * @var FilesystemInterface $fileSystem The file system to use.
     */
    private $fileSystem;

    /**
     * @var FileValidatorInterface $fileValidator The file validator to know which files we need to parse.
     */
    private $fileValidator;

    /**
     * @var ReportInterface $report The report to use.
     */
    private $report;

    /**
     * DirectoryParser constructor.
     *
     * @param ConsoleConfigInterface $config        A config instance.
     * @param StyleInterface         $output        An output to display message.
     * @param ExecutorInterface      $executor      A PhpUnitGen executor.
     * @param FilesystemInterface    $fileSystem    A file system instance.
     * @param FileValidatorInterface $fileValidator A file validator.
     * @param ReportInterface        $report        The report to use.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        StyleInterface $output,
        ExecutorInterface $executor,
        FilesystemInterface $fileSystem,
        FileValidatorInterface $fileValidator,
        ReportInterface $report
    ) {
        $this->config        = $config;
        $this->output        = $output;
        $this->executor      = $executor;
        $this->fileSystem    = $fileSystem;
        $this->fileValidator = $fileValidator;
        $this->report        = $report;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(string $sourcePath, string $targetPath, string $name): bool
    {
        if (! $this->fileValidator->validate($sourcePath)) {
            return false;
        }

        $content = $this->fileSystem->read($sourcePath);

        if ($content === false) {
            throw new ParseException(sprintf('The file "%s" is not readable', $sourcePath));
        }

        // We ignore the type checked because we already check the readability
        $code = $this->executor->invoke($content, $name);

        if ($code === null) {
            $this->output->note(sprintf('Parsing file "%s" completed: no testable functions in code', $sourcePath));
            return false;
        }

        $this->checkTargetPath($targetPath);

        $this->fileSystem->write($targetPath, $code);

        // Output that a file is parsed
        $this->output->text(sprintf('Parsing file "%s" completed', $sourcePath));

        $this->report->increaseParsedFileNumber();

        return true;
    }

    /**
     * Check if an old file exists. If overwrite option is activated, delete it, else, throw an exception.
     *
     * @param string $targetPath The target file path.
     *
     * @throws FileExistsException If overwrite option is deactivated and file exists.
     */
    public function checkTargetPath(string $targetPath): void
    {
        if ($this->fileSystem->has($targetPath)) {
            if (! $this->config->hasOverwrite()) {
                throw new FileExistsException(sprintf('The target file "%s" already exists', $targetPath));
            }
            if ($this->config->hasBackup()) {
                $backupTarget = $targetPath . '.bak';
                if ($this->fileSystem->has($backupTarget)) {
                    throw new FileExistsException(sprintf(
                        'The backup target file "%s" already exists',
                        $backupTarget
                    ));
                }
                $this->fileSystem->copy($targetPath, $backupTarget);
            }
            $this->fileSystem->delete($targetPath);
        }
    }
}
