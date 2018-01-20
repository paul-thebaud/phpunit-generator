<?php

namespace PhpUnitGen\Container\ContainerInterface;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Interface ConsoleContainerFactoryInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ConsoleContainerFactoryInterface
{
    /**
     * Build a new instance of the container.
     *
     * @param ConsoleConfigInterface $config    A configuration instance.
     * @param StyleInterface         $output    An output to display messages.
     * @param Stopwatch              $stopwatch The stopwatch instance to measure duration and memory usage.
     *
     * @return ContainerInterface The created container.
     */
    public function invoke(
        ConsoleConfigInterface $config,
        StyleInterface $output,
        Stopwatch $stopwatch
    ): ContainerInterface;
}
