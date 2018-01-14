<?php

namespace PhpUnitGen\Container\ContainerInterface;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Interface ContainerInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * Add to available services a resolver to retrieve an object instance.
     *
     * @param string   $id       The service identifier.
     * @param callable $resolver A resolver which return an object instance.
     */
    public function setResolver(string $id, callable $resolver): void;

    /**
     * Add to available services an instance of an object.
     *
     * @param string $id       The service identifier.
     * @param object $instance An object instance.
     */
    public function setInstance(string $id, object $instance): void;

    /**
     * Add to available services a class which can be construct by the container resolve method.
     *
     * @param string      $id    The service identifier.
     * @param string|null $class The class name, null if it can $id as a class name.
     */
    public function set(string $id, string $class = null): void;
}
