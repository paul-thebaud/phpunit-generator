<?php

namespace PhpUnitGen\Container\ContainerInterface;

use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Interface ContainerFactoryInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ContainerFactoryInterface
{
    /**
     * Build a new instance of the container.
     *
     * @param ConfigInterface $config A configuration instance.
     *
     * @return ContainerInterface The created container.
     */
    public function invoke(
        ConfigInterface $config
    ): ContainerInterface;
}
