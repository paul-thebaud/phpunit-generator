<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Model;

use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ModifierInterface;

/**
 * Class ClassModel
 *
 *      An implementation of ClassModelInterface
 *
 * @package PHPUnitGenerator\Model
 */
class ClassModel implements ClassModelInterface
{
    /**
     * @var string $namespaceName The class namespace name
     */
    private $namespaceName = null;

    /**
     * @var string $name The class name
     */
    private $name;

    /**
     * @var string $type The class type
     */
    private $type = self::TYPE_CLASS;

    /**
     * @var string $modifier The class modifier
     */
    private $modifier = null;

    /**
     * @var string[] $properties The class properties
     */
    private $properties = [];

    /**
     * @var MethodModelInterface[] $methods The class methods
     */
    private $methods = [];

    /**
     * ClassModel constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * {@inheritdoc}
     */
    public function setNamespaceName(string $namespaceName)
    {
        $this->namespaceName = $namespaceName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModifier()
    {
        return $this->modifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setModifier(string $modifier)
    {
        $this->modifier = $modifier;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProperty(string $name): bool
    {
        foreach ($this->getProperties() as $property) {
            if ($name === $property) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(string $name)
    {
        foreach ($this->getMethods() as $method) {
            if ($name === $method->getName()) {
                return $method;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMethod(string $name): bool
    {
        foreach ($this->getMethods() as $method) {
            if ($name === $method->getName()) {
                return true;
            }
        }
        return false;
    }

    /*
     **********************************************************************
     *
     * Methods which use properties
     *
     **********************************************************************
     */

    /**
     * {@inheritdoc}
     */
    public function getCompleteName(): string
    {
        return sprintf(
            '%s%s',
            $this->getNamespaceName() ? ($this->getNamespaceName() . '\\') : '',
            $this->getName()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isInterface(): bool
    {
        return self::TYPE_INTERFACE === $this->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function isTrait(): bool
    {
        return self::TYPE_TRAIT === $this->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function isAbstract(): bool
    {
        return ModifierInterface::MODIFIER_ABSTRACT === $this->getModifier();
    }
}
