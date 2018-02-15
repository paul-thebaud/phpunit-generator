<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Container;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
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
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Stopwatch\Stopwatch;

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
    /**
     * @var ContainerFactory $containerFactory The basic container factory.
     */
    private $containerFactory;

    /**
     * ConsoleContainerFactory constructor.
     *
     * @param ContainerFactory $containerFactory The basic container factory.
     */
    public function __construct(ContainerFactory $containerFactory)
    {
        $this->containerFactory = $containerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(
        ConsoleConfigInterface $config,
        StyleInterface $styledIO,
        Stopwatch $stopwatch
    ): ContainerInterface {
        /** @var Container $container */
        $container = $this->containerFactory->invoke($config);

        $container->setInstance(StyleInterface::class, $styledIO);
        $container->setInstance(Stopwatch::class, $stopwatch);
        $container->setInstance(FilesystemInterface::class, new Filesystem(new Local('./')));

        $container->set(ExceptionCatcherInterface::class, ExceptionCatcher::class);
        $container->set(FileValidatorInterface::class, FileValidator::class);
        $container->set(FileExecutorInterface::class, FileExecutor::class);
        $container->set(DirectoryExecutorInterface::class, DirectoryExecutor::class);
        $container->set(ConsoleExecutorInterface::class, ConsoleExecutor::class);

        return $container;
    }
}
