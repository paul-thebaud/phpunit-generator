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
    private $customResolvable = [];

    /**
     * @var string[] $autoResolvable All objects resolvable automatically.
     */
    private $autoResolvable = [];

    /**
     * @var object[] $instances All objects instances.
     */
    private $instances = [];

    /**
     * {@inheritdoc}
     */
    public function setResolver(string $id, callable $resolver): void
    {
        $this->customResolvable[$id] = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function setInstance(string $id, object $instance): void
    {
        $this->instances[$id] = $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $id, string $class = null): void
    {
        $this->autoResolvable[$id] = $class ?? $id;
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
            throw new ContainerException("Identifier is not a string.");
        }
        if (Validator::key($id)->validate($this->instances)) {
            return $this->instances[$id];
        }
        if (Validator::key($id)->validate($this->customResolvable)) {
            return $this->instances[$id] = $this->customResolvable[$id]($this);
        }
        if (Validator::key($id)->validate($this->autoResolvable)) {
            return $this->instances[$id] = $this->autoResolve($this->autoResolvable[$id]);
        }
        throw new NotFoundException(sprintf('Service of identifier "%s" not found.', $id));
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
            throw new ContainerException(sprintf("Class %s does not exists.", $class));
        }

        $reflection = new \ReflectionClass($class);

        if (! $reflection->isInstantiable()) {
            throw new ContainerException(sprintf("Class %s is not instantiable.", $class));
        }
        if (($constructor = $reflection->getConstructor()) === null) {
            return $reflection->newInstance();
        }
        $constructorParameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $constructorParameters[] = $this->resolve($parameter->getClass()->getName());
        }
        return $reflection->newInstanceArgs($constructorParameters);
    }
}
