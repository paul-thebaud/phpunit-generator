<?php

namespace PhpUnitGen\Model\PropertyTrait;

use Respect\Validation\Validator;

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
     * @var string[] $namespace A string[] describing a namespace.
     */
    protected $namespace = [];

    /**
     * @param string[] $namespace The new namespace to be set.
     */
    public function setNamespace(array $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string[] The current namespace.
     */
    public function getNamespace(): array
    {
        return $this->namespace;
    }

    /**
     * @return string|null The concat namespace parts.
     */
    public function getNamespaceString(): ?string
    {
        return Validator::notEmpty()->validate($this->namespace)?
            implode('\\', $this->namespace) :
            null;
    }

    /**
     * @return string|null The last namespace part.
     */
    public function getNamespaceLast(): ?string
    {
        return Validator::notEmpty()->validate($this->namespace)?
            $this->namespace[(count($this->namespace) - 1)] :
            null;
    }
}
