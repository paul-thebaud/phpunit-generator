<?php

namespace PhpUnitGen\Container;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConsoleConfigInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
    public function invoke(ConsoleConfigInterface $config, OutputInterface $output): ContainerInterface
    {
        $container = $this->containerFactory->invoke($config);

        $container->setInstance(FilesystemInterface::class, new Filesystem(new Local('./')));
        $container->setInstance(OutputInterface::class, $output);

        return $container;
    }
}
