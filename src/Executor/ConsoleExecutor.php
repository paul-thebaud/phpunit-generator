<?php

namespace PhpUnitGen\Executor;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Exception\ExecutorException;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;

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

    public function __construct(
        ConsoleConfigInterface $config,
        DirectoryExecutorInterface $directoryExecutor,
        FileExecutorInterface $fileExecutor,
        ExceptionCatcherInterface $exceptionCatcher
    ) {
        $this->config            = $config;
        $this->directoryExecutor = $directoryExecutor;
        $this->fileExecutor      = $fileExecutor;
        $this->exceptionCatcher  = $exceptionCatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): void
    {
        foreach ($this->config->getDirectories() as $source => $target) {
            try {
                $this->directoryExecutor->execute($source, $target);
            } catch (ExecutorException $exception) {
                $this->exceptionCatcher->catch($exception);
            }
        }
        foreach ($this->config->getFiles() as $source => $target) {
            try {
                $this->fileExecutor->execute($source, $target);
            } catch (ExecutorException $exception) {
                $this->exceptionCatcher->catch($exception);
            }
        }
    }
}