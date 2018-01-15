<?php

namespace PhpUnitGen\Container;

use PhpUnitGen\Container\ContainerInterface\ContainerInterface;
use PhpUnitGen\Exception\ContainerException;
use PhpUnitGen\Exception\NotFoundException;
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
     * @var callable[] $customResolvable All objects resolvable from a callable.
     */
    private static $customResolvable = [];

    /**
     * @var string[] $autoResolvable All objects resolvable automatically.
     */
    private static $autoResolvable = [];

    /**
     * @var object[] $instances All objects instances.
     */
    private static $instances = [];

    /**
     * {@inheritdoc}
     */
    public function setResolver(string $id, callable $resolver): void
    {
        self::$customResolvable[$id] = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function setInstance(string $id, object $instance): void
    {
        self::$instances[$id] = $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $id, string $class = null): void
    {
        self::$autoResolvable[$id] = $class ?? $id;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id): object
    {
        return $this->resolve($id);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        try {
            $this->resolve($id);
        } catch (NotFoundException $exception) {
            return false;
        } catch (ContainerException $exception) {
            return false;
        }
        return true;
    }

    /**
     * Try to retrieve a service instance.
     *
     * @param string $id The service identifier.
     *
     * @return object The service.
     *
     * @throws ContainerException If the service identifier is not a string.
     * @throws NotFoundException If the service does not exists.
     */
    private function resolve($id): object
    {
        if (! Validator::stringType()->validate($id)) {
            throw new ContainerException('Identifier is not a string.');
        }
        return $this->resolveInstance($id);
    }

    /**
     * Try to retrieve a service instance from the instances array.
     *
     * @param string $id The service identifier.
     *
     * @return object The service.
     *
     * @throws ContainerException If the service identifier is not a string.
     */
    private function resolveInstance(string $id): object
    {
        if (Validator::key($id)->validate(self::$instances)) {
            return self::$instances[$id];
        }
        return $this->resolveCustomResolvable($id);
    }

    /**
     * Try to retrieve a service instance from the custom resolvable array.
     *
     * @param string $id The service identifier.
     *
     * @return object The service.
     *
     * @throws ContainerException If the service identifier is not a string.
     */
    private function resolveCustomResolvable(string $id): object
    {
        if (Validator::key($id)->validate(self::$customResolvable)) {
            return self::$instances[$id] = (self::$customResolvable[$id])($this);
        }
        return $this->resolveAutomaticResolvable($id);
    }

    /**
     * Try to retrieve a service instance from the automatic resolvable array.
     *
     * @param string $id The service identifier.
     *
     * @return object The service.
     *
     * @throws ContainerException If the service identifier is not a string.
     */
    private function resolveAutomaticResolvable(string $id): object
    {
        if (Validator::key($id)->validate(self::$autoResolvable)) {
            return self::$instances[$id] = $this->autoResolve(self::$autoResolvable[$id]);
        }
        return $this->autoResolve($id);
    }

    /**
     * Try to automatically create a service.
     *
     * @param string $class The service class.
     *
     * @return object
     *
     * @throws ContainerException If the service cannot be constructed.
     */
    private function autoResolve(string $class): object
    {
        if (! class_exists($class)) {
            throw new ContainerException(sprintf('Class "%s" does not exists.', $class));
        }

        $reflection = new \ReflectionClass($class);

        if (! $reflection->isInstantiable()) {
            throw new ContainerException(sprintf('Class "%s" is not instantiable.', $class));
        }
        return $this->buildInstance($reflection);
    }

    /**
     * Build a new instance of a class from reflection class and auto-resolved constructor arguments.
     *
     * @param \ReflectionClass $reflection The reflection class.
     *
     * @return object The built instance.
     *
     * @throws ContainerException If the class constructor is not public.
     */
    private function buildInstance(\ReflectionClass $reflection): object
    {
        if (($constructor = $reflection->getConstructor()) === null) {
            return $reflection->newInstance();
        }
        if (! $constructor->isPublic()) {
            throw new ContainerException(
                sprintf('Class "%s" has no public constructor.', $reflection->getName())
            );
        }
        $constructorParameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $constructorParameters[] = $this->resolve($parameter->getClass()->getName());
        }
        return $reflection->newInstanceArgs($constructorParameters);
    }
}
