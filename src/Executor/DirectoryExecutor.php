<?php

namespace PhpUnitGen\Executor;

use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Exception\ExecutorException;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class DirectoryExecutor.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DirectoryExecutor implements DirectoryExecutorInterface
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
     * @var FileExecutorInterface $fileExecutor The file executor to parse files.
     */
    private $fileExecutor;

    /**
     * @var FilesystemInterface $fileSystem The file system to use.
     */
    private $fileSystem;

    /**
     * @var ExceptionCatcherInterface $exceptionCatcher An exception catcher to catch exception.
     */
    private $exceptionCatcher;

    /**
     * @var ReportInterface $report The report to use.
     */
    private $report;

    /**
     * DirectoryParser constructor.
     *
     * @param ConsoleConfigInterface    $config           A config instance.
     * @param StyleInterface            $output           An output to display message.
     * @param FileExecutorInterface     $fileExecutor     A file executor.
     * @param FilesystemInterface       $fileSystem       A file system instance.
     * @param ExceptionCatcherInterface $exceptionCatcher The exception catcher.
     * @param ReportInterface           $report           The report.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        StyleInterface $output,
        FileExecutorInterface $fileExecutor,
        FilesystemInterface $fileSystem,
        ExceptionCatcherInterface $exceptionCatcher,
        ReportInterface $report
    ) {
        $this->config           = $config;
        $this->output           = $output;
        $this->fileExecutor     = $fileExecutor;
        $this->fileSystem       = $fileSystem;
        $this->exceptionCatcher = $exceptionCatcher;
        $this->report           = $report;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(string $sourcePath, string $targetPath): void
    {
        $this->output->section(sprintf('Directory "%s" parsing begins.', $sourcePath));

        // Check if source directory exists
        if (! $this->fileSystem->has($sourcePath) || ! $this->fileSystem->getMimetype($sourcePath) === 'directory') {
            throw new ExecutorException(sprintf('The source directory "%s" does not exist.', $sourcePath));
        }

        // List content of directory
        foreach ($this->fileSystem->listContents($sourcePath, true) as $file) {
            $this->executeFileExecutor($sourcePath, $targetPath, $file['path']);
        }

        $this->report->increaseParsedFileFromDirectoryNumber();
    }

    /**
     * Execute the file executor for a file in a directory.
     *
     * @param string $sourcePath The directory source path.
     * @param string $targetPath The directory target path.
     * @param string $filePath   The file path.
     *
     * @throws ExecutorException If there was an error during the process.
     */
    private function executeFileExecutor(string $sourcePath, string $targetPath, string $filePath): void
    {
        try {
            // Execute file executor
            $this->fileExecutor->invoke($filePath, str_replace($sourcePath, $targetPath, $filePath));
        } catch (Exception $exception) {
            $this->exceptionCatcher->catch($exception, $sourcePath);
        }
    }
}
