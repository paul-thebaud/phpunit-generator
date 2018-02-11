<?php

namespace PhpUnitGen\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var AttributeModelInterface[]|Collection $attributes Class attributes.
     */
    private $attributes;

    /**
     * TraitModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->attributes = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addAttribute(AttributeModelInterface $attribute): void
    {
        $this->attributes->add($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttribute(string $name, bool $static = false): bool
    {
        return $this->attributes->exists(function (int $key, AttributeModelInterface $attribute) use ($name, $static) {
            return $attribute->getName() === $name && $attribute->isStatic() === $static;
        });
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
}
