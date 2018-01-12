<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\AttributeModelInterface;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;

/**
 * Class TraitModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class TraitModel extends InterfaceModel implements TraitModelInterface
{
    /**
     * @var AttributeModel[] $attributes Class attributes.
     */
    private $attributes = [];

    /**
     * {@inheritdoc}
     */
    public function addAttribute(AttributeModelInterface $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttribute(string $name): bool
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute(string $name): ?AttributeModelInterface
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}