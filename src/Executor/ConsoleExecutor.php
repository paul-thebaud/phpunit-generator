<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Executor;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Console\GenerateCommand;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class ConsoleExecutor.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ConsoleExecutor implements ConsoleExecutorInterface
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
     * @var Stopwatch $stopwatch The stopwatch to measure duration and memory usage.
     */
    private $stopwatch;

    /**
     * @var DirectoryExecutorInterface $directoryExecutor A directory executor.
     */
    private $directoryExecutor;

    /**
     * @var FileExecutorInterface $fileExecutor A file executor.
     */
    private $fileExecutor;

    /**
     * @var ExceptionCatcherInterface $exceptionCatcher An exception catcher to catch exception.
     */
    private $exceptionCatcher;

    /**
     * @var ReportInterface $report The report to use.
     */
    private $report;

    /**
     * ConsoleExecutor constructor.
     *
     * @param ConsoleConfigInterface     $config            The config to use.
     * @param StyleInterface             $output            The output to use.
     * @param Stopwatch                  $stopwatch         The stopwatch to use.
     * @param DirectoryExecutorInterface $directoryExecutor The directory executor.
     * @param FileExecutorInterface      $fileExecutor      The file executor.
     * @param ExceptionCatcherInterface  $exceptionCatcher  The exception catcher.
     * @param ReportInterface            $report            The report.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        StyleInterface $output,
        Stopwatch $stopwatch,
        DirectoryExecutorInterface $directoryExecutor,
        FileExecutorInterface $fileExecutor,
        ExceptionCatcherInterface $exceptionCatcher,
        ReportInterface $report
    ) {
        $this->config            = $config;
        $this->output            = $output;
        $this->stopwatch         = $stopwatch;
        $this->directoryExecutor = $directoryExecutor;
        $this->fileExecutor      = $fileExecutor;
        $this->exceptionCatcher  = $exceptionCatcher;
        $this->report            = $report;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(): void
    {
        if (count($this->config->getDirectories()) > 0) {
            $this->output->section('Directories parsing begins.');

            $this->executeOnDirectories();
        }

        if (count($this->config->getFiles()) > 0) {
            $this->output->section('Global files parsing begins.');

            $this->executeOnFiles();
        }

        $event = $this->stopwatch->stop(GenerateCommand::STOPWATCH_EVENT);

        $this->output->section('PhpUnitGen finished all tasks.');
        $this->output->text(sprintf(
            '<options=bold,underscore>Duration:</> %d milliseconds',
            $event->getDuration()
        ));
        $this->output->text(sprintf(
            '<options=bold,underscore>Memory usage:</> %d bytes',
            $event->getMemory()
        ));
        $this->output->text(sprintf(
            '<options=bold,underscore>Parsed files number:</> %d files',
            $this->report->getParsedFileNumber()
        ));
        $this->output->text(sprintf(
            '<options=bold,underscore>Parsed directories number:</> %d directories',
            $this->report->getParsedDirectoryNumber()
        ));
        $this->output->text(sprintf(
            '<options=bold,underscore>Errors during process:</> %d errors',
            $this->report->getIgnoredErrorNumber()
        ));
        $this->output->newLine();
    }

    /**
     * Execute PhpUnitGen tasks on directories.
     *
     * @throws Exception If an error occurred during process.
     */
    private function executeOnDirectories(): void
    {
        foreach ($this->config->getDirectories() as $source => $target) {
            try {
                $this->directoryExecutor->invoke($source, $target);
                $this->report->increaseParsedDirectoryNumber();
            } catch (Exception $exception) {
                $this->exceptionCatcher->catch($exception, $source);
            }
        }
    }

    /**
     * Execute PhpUnitGen tasks on files.
     *
     * @throws Exception If an error occurred during process.
     */
    private function executeOnFiles(): void
    {
        foreach ($this->config->getFiles() as $source => $target) {
            try {
                $name = pathinfo($target)['filename'];
                $this->fileExecutor->invoke($source, $target, $name);
            } catch (Exception $exception) {
                $this->exceptionCatcher->catch($exception, $source);
            }
        }
    }
}
