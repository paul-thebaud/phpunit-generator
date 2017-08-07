<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Model\ModelInterface;

/**
 * Interface ClassModelInterface
 *
 *      Specifies which methods will contains a ClassModel
 *      A ClassModel is a representation of a PHP class, abstract class,
 *      trait or interface.
 *
 * @package PHPUnitGenerator\Model\ModelInterface
 */
interface ClassModelInterface
{
    /**
     * @var string TYPE_INTERFACE Type interface
     */
    const TYPE_INTERFACE = 'interface';

    /**
     * @var string TYPE_CLASS Type class
     */
    const TYPE_CLASS = 'class';

    /**
     * @var string TYPE_TRAIT Type trait
     */
    const TYPE_TRAIT = 'trait';

    /**
     * Get the class namespace name if exists, else return null
     *
     * @return string|null
     */
    public function getNamespaceName();

    /**
     * Set the class namespace name
     *
     * @param string $namespaceName
     *
     * @return ClassModelInterface
     */
    public function setNamespaceName(string $namespaceName);

    /**
     * Get the class name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the class type as a string
     * (presents in self constants named TYPE_<type>)
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set the class type
     *
     * @param string $type
     *
     * @return ClassModelInterface
     */
    public function setType(string $type);

    /**
     * Get the class modifier as a string
     * (presents in ModifierInterface constants named MODIFIER_<type>)
     *
     * @return string
     */
    public function getModifier();

    /**
     * Set the class modifier
     *
     * @param string $modifier
     *
     * @return ClassModelInterface
     */
    public function setModifier(string $modifier);

    /**
     * Get the class properties
     *
     * @return string[]
     */
    public function getProperties(): array;

    /**
     * Get the class properties
     *
     * @param string[] $properties
     *
     * @return ClassModelInterface
     */
    public function setProperties(array $properties);

    /**
     * Check if the class has a property
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty(string $name): bool;

    /**
     * Get the class methods
     *
     * @return MethodModelInterface[]
     */
    public function getMethods(): array;

    /**
     * Get the method if the class has it
     *
     * @param string $name
     *
     * @return MethodModelInterface|null
     */
    public function getMethod(string $name);

    /**
     * Set the class methods
     *
     * @param MethodModelInterface[] $methods
     *
     * @return ClassModelInterface
     */
    public function setMethods(array $methods);

    /**
     * Check if the class has a method
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasMethod(string $name): bool;

    /**
     * Get the tests class PHPDoc annotations
     *
     * @return string[]
     */
    public function getTestsAnnotations(): array;

    /**
     * Get the tests class PHPDoc annotations
     *
     * @param string[] $testsAnnotations
     *
     * @return ClassModelInterface
     */
    public function setTestsAnnotations(array $testsAnnotations);

    /*
     **********************************************************************
     *
     * Methods that use properties
     *
     **********************************************************************
     */

    /**
     * Get the complete class name with following format
     * [<namespaceName>\]<className>
     *
     * @return string
     */
    public function getCompleteName(): string;

    /**
     * Check if the class corresponds to an Interface
     *
     * @return bool
     */
    public function isInterface(): bool;

    /**
     * Check if the class corresponds to an Trait
     *
     * @return bool
     */
    public function isTrait(): bool;

    /**
     * Check if the class is abstract
     *
     * @return bool
     */
    public function isAbstract(): bool;
}
