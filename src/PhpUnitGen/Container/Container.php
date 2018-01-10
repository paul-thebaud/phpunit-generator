<?php

namespace PhpUnitGen\Container;

use PhpUnitGen\Exception\ContainerException;
use PhpUnitGen\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

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
     * @var callable[] $registry All key => resolver.
     */
    private $registry = [];

    /**
     * @var object[] $instances All already created object instances.
     */
    private $instances = [];

    /**
     * Save a new key => resolver.
     *
     * @param string   $key      A key to retrieve the $resolver and the instance.
     * @param callable $resolver A resolver which return an object instance.
     */
    public function setResolver(string $key, callable $resolver): void
    {
        $this->registry[$key] = $resolver;
    }

    /**
     * Save a new instance immediately.
     *
     * @param string $key      A key to retrieve the instance.
     * @param object $instance An object instance to save.
     */
    public function setInstance(string $key, object $instance): void
    {
        $this->instances[$key] = $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id): object
    {
        if (! is_string($id)) {
            throw new ContainerException('Given identifier to container is not a string.');
        }
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }
        if (isset($this->registry[$id])) {
            return $this->instances[$id] = $this->registry[$id]($this);
        }
        throw new NotFoundException(sprintf('Identifier "%s" not found in the container.', $id));
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        if (! is_string($id)) {
            return false;
        }
        if (isset($this->instances[$id])) {
            return true;
        }
        if (isset($this->registry[$id])) {
            return true;
        }
        return false;
    }
}
