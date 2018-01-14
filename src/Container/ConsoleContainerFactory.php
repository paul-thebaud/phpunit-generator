<?php

namespace PhpUnitGen\Container;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
use PhpUnitGen\Container\ContainerInterface\ContainerInterface;
use PhpUnitGen\Exception\ExceptionCatcher;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use PhpUnitGen\Executor\ConsoleExecutor;
use PhpUnitGen\Executor\DirectoryExecutor;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\DirectoryExecutorInterface;
use PhpUnitGen\Executor\ExecutorInterface\FileExecutorInterface;
use PhpUnitGen\Executor\FileExecutor;
use PhpUnitGen\Validator\FileValidator;
use PhpUnitGen\Validator\ValidatorInterface\FileValidatorInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class ConsoleContainerFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ConsoleContainerFactory implements ConsoleContainerFactoryInterface
{
    private $containerFactory;

    public function __construct()
    {
        $this->containerFactory = new ContainerFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(
        ConsoleConfigInterface $config,
        StyleInterface $output
    ): ContainerInterface {
        $container = $this->containerFactory->invoke($config);

        $container->setInstance(FilesystemInterface::class, new Filesystem(new Local('./')));
        $container->setInstance(StyleInterface::class, $output);

        $container->set(ExceptionCatcherInterface::class, ExceptionCatcher::class);
        $container->set(FileValidatorInterface::class, FileValidator::class);
        $container->set(FileExecutorInterface::class, FileExecutor::class);
        $container->set(DirectoryExecutorInterface::class, DirectoryExecutor::class);
        $container->set(ConsoleExecutorInterface::class, ConsoleExecutor::class);

        return $container;
    }
}
