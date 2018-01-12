<?php

namespace PhpUnitGen\Model\PropertyTrait;

/**
 * Trait NamespaceTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait NamespaceTrait
{
    /**
     * @var string[]|null $namespace A string[] describing a namespace.
     */
    protected $namespace;

    /**
     * @param string[]|null $namespace The new namespace to be set.
     */
    public function setNamespace(?array $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string[]|null The current namespace.
     */
    public function getNamespace(): ?array
    {
        return $this->namespace;
    }
}
