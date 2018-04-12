<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

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
     * @param StyleInterface         $styledIO  The styled I/O interface implementation.
     * @param Stopwatch              $stopwatch The stopwatch instance to measure duration and memory usage.
     *
     * @return ContainerInterface The created container.
     */
    public function invoke(
        ConsoleConfigInterface $config,
        StyleInterface $styledIO,
        Stopwatch $stopwatch
    ): ContainerInterface;
}
