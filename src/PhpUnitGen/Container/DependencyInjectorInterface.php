<?php

namespace PhpUnitGen\Container;

use PhpUnitGen\Configuration\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Interface DependencyInjectorInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface DependencyInjectorInterface
{
    /**
     * Will inject all dependencies needed for PhpUnitGen in a container.
     *
     * @param ConfigInterface    $config    The configuration to use to inject dependencies.
     * @param ContainerInterface $container The container to use to inject dependencies.
     *
     * @return ContainerInterface The container with injected dependencies.
     */
    public function inject(ConfigInterface $config, ContainerInterface $container): ContainerInterface;
}