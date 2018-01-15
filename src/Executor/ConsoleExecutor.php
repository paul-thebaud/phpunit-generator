<?php

namespace PhpUnitGen\Executor;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
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
     * ConsoleExecutor constructor.
     *
     * @param ConsoleConfigInterface     $config            The config to use.
     * @param StyleInterface             $output            The output to use.
     * @param Stopwatch                  $stopwatch         The stopwatch to use.
     * @param DirectoryExecutorInterface $directoryExecutor The directory executor.
     * @param FileExecutorInterface      $fileExecutor      The file executor.
     * @param ExceptionCatcherInterface  $exceptionCatcher  The exception catcher.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        StyleInterface $output,
        Stopwatch $stopwatch,
        DirectoryExecutorInterface $directoryExecutor,
        FileExecutorInterface $fileExecutor,
        ExceptionCatcherInterface $exceptionCatcher
    ) {
        $this->config            = $config;
        $this->output            = $output;
        $this->stopwatch         = $stopwatch;
        $this->directoryExecutor = $directoryExecutor;
        $this->fileExecutor      = $fileExecutor;
        $this->exceptionCatcher  = $exceptionCatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(): void
    {
        $this->executeOnDirectories();

        $this->output->section('Global files parsing begins.');

        $this->executeOnFiles();

        $event = $this->stopwatch->stop('command');

        $this->output->section('PhpUnitGen finished all tasks.');
        $this->output->text(sprintf(
            '<options=bold,underscore>Duration:</> %d milliseconds',
            $event->getDuration()
        ));
        $this->output->text(sprintf(
            '<options=bold,underscore>Memory usage:</> %d bytes',
            $event->getMemory()
        ));
        $this->output->newLine();
    }

    /**
     * Execute PhpUnitGen tasks on directories.
     */
    private function executeOnDirectories(): void
    {
        foreach ($this->config->getDirectories() as $source => $target) {
            try {
                $this->directoryExecutor->invoke($source, $target);
            } catch (Exception $exception) {
                $this->exceptionCatcher->catch($exception);
            }
        }
    }

    /**
     * Execute PhpUnitGen tasks on files.
     */
    private function executeOnFiles(): void
    {
        foreach ($this->config->getFiles() as $source => $target) {
            try {
                $this->fileExecutor->invoke($source, $target);
            } catch (Exception $exception) {
                $this->exceptionCatcher->catch($exception);
            }
        }
    }
}
