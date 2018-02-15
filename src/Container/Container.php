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

use PhpUnitGen\Exception\ContainerException;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator;

/**
 * Class Container.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class Container implements ContainerInterface
{
    /**
     * @var string[] $autoResolvable All objects resolvable automatically.
     */
    private $autoResolvable = [];

    /**
     * @var mixed[] $instances All objects instances.
     */
    private $instances = [];

    /**
     * Add to available services an instance of an object.
     *
     * @param string $id       The service identifier.
     * @param mixed  $instance An object instance.
     */
    public function setInstance(string $id, $instance): void
    {
        $this->instances[$id] = $instance;
    }

    /**
     * Add to available services a class which can be construct by the container resolve method.
     *
     * @param string      $id    The service identifier.
     * @param string|null $class The class name, null if it can $id as a class name.
     */
    public function set(string $id, string $class = null): void
    {
        $this->autoResolvable[$id] = $class ?? $id;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        try {
            $this->get($id);
        } catch (ContainerException $exception) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (! Validator::stringType()->validate($id)) {
            throw new ContainerException('Identifier is not a string');
        }
        return $this->resolveInstance($id);
    }

    /**
     * Try to retrieve a service instance from the instances array.
     *
     * @param string $id The service identifier.
     *
     * @return mixed The service.
     *
     * @throws ContainerException If the service identifier is not a string.
     */
    private function resolveInstance(string $id)
    {
        if (Validator::key($id)->validate($this->instances)) {
            return $this->instances[$id];
        }
        return $this->resolveAutomaticResolvable($id);
    }

    /**
     * Try to retrieve a service instance from the automatic resolvable array.
     *
     * @param string $id The service identifier.
     *
     * @return mixed The service.
     *
     * @throws ContainerException If the service identifier is not a string.
     */
    private function resolveAutomaticResolvable(string $id)
    {
        if (Validator::key($id)->validate($this->autoResolvable)) {
            return $this->instances[$id] = $this->autoResolve($this->autoResolvable[$id]);
        }
        return $this->autoResolve($id);
    }

    /**
     * Try to automatically create a service.
     *
     * @param string $class The service class.
     *
     * @return mixed The built instance.
     *
     * @throws ContainerException If the service cannot be constructed.
     */
    private function autoResolve(string $class)
    {
        try {
            $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $exception) {
            throw new ContainerException(sprintf('Class "%s" does not exists', $class));
        }

        if (! $reflection->isInstantiable()) {
            throw new ContainerException(sprintf('Class "%s" is not instantiable', $class));
        }
        return $this->buildInstance($reflection);
    }

    /**
     * Build a new instance of a class from reflection class and auto-resolved constructor arguments.
     *
     * @param \ReflectionClass $reflection The reflection class.
     *
     * @return mixed The built instance.
     *
     * @throws ContainerException If the class constructor is not public.
     */
    private function buildInstance(\ReflectionClass $reflection)
    {
        if (($constructor = $reflection->getConstructor()) === null) {
            return $reflection->newInstance();
        }
        $constructorParameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            if (($parameterClass = $parameter->getClass()) === null) {
                throw new ContainerException(sprintf(
                    'Class "%s" constructor has a scalar / callable / array type parameter',
                    $reflection->getName()
                ));
            }
            $constructorParameters[] = $this->get($parameterClass->getName());
        }
        return $reflection->newInstanceArgs($constructorParameters);
    }
}
