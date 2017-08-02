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
 * Interface MethodModelInterface
 *
 *      Specifies which methods will contains a MethodModel
 *      An MethodModel is a representation of a PHP method
 *      inside a class, abstract class, trait or interface.
 *
 * @package PHPUnitGenerator\Model\ModelInterface
 */
interface MethodModelInterface
{
    /**
     * @var string VISIBILITY_PUBLIC The visibility public
     */
    const VISIBILITY_PUBLIC = 'public';

    /**
     * @var string VISIBILITY_PROTECTED The visibility protected
     */
    const VISIBILITY_PROTECTED = 'protected';

    /**
     * @var string VISIBILITY_PRIVATE The visibility private
     */
    const VISIBILITY_PRIVATE = 'private';

    /**
     * Get the method name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the method visibility as a string
     * (presents in self constants named VISIBILITY_<visibility>)
     *
     * @return string
     */
    public function getVisibility(): string;

    /**
     * Set the method visibility
     *
     * @param string $visibility
     *
     * @return MethodModelInterface
     */
    public function setVisibility(string $visibility);

    /**
     * Get the method modifier as a string
     * (presents in ModifierInterface constants named MODIFIER_<type>)
     *
     * @return string
     */
    public function getModifier(): string;

    /**
     * Set the method modifier
     *
     * @param string $modifier
     *
     * @return MethodModelInterface
     */
    public function setModifier(string $modifier);

    /**
     * Get the method arguments
     *
     * @return ArgumentModelInterface[]
     */
    public function getArguments(): array;

    /**
     * Set the class arguments
     *
     * @param ArgumentModelInterface[] $arguments
     *
     * @return MethodModelInterface
     */
    public function setArguments(array $arguments);

    /**
     * Get the method return type as a string
     * (presents in TypeInterface constants named TYPE_<type>)
     *
     * @see \PHPUnitGenerator\Model\ModelInterface\TypeInterface
     *
     * @return string
     */
    public function getReturnType(): string;

    /**
     * Set the method return type
     *
     * @param string $returnType
     *
     * @return MethodModelInterface
     */
    public function setReturnType(string $returnType);

    /**
     * Get a boolean which tells if the return value can be null
     *
     * @return bool
     */
    public function getCanBeNullReturn(): bool;

    /**
     * Set the $canBeNullReturn boolean
     *
     * @param bool $canBeNullReturn
     *
     * @return MethodModelInterface
     */
    public function setCanBeNullReturn(bool $canBeNullReturn);

    /**
     * Get the method documentation if exists, else return null
     *
     * @return string|null
     */
    public function getDocumentation();

    /**
     * Set the method documentation
     *
     * @param string $documentation
     *
     * @return MethodModelInterface
     */
    public function setDocumentation(string $documentation);

    /**
     * Get the method documentation annotations
     *
     * @return AnnotationModelInterface[]
     */
    public function getAnnotations(): array;

    /**
     * Set the class documentation annotations
     *
     * @param AnnotationModelInterface[] $annotations
     *
     * @return MethodModelInterface
     */
    public function setAnnotations(array $annotations);

    /**
     * Get the class which implements this method
     *
     * @return ClassModelInterface
     */
    public function getParentClass(): ClassModelInterface;

    /*
     **********************************************************************
     *
     * Methods which use properties
     *
     **********************************************************************
     */

    /**
     * Get the test method name
     *
     * @return string
     */
    public function getTestName(): string;

    /**
     * Check if the method is public
     *
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * Check if the method is abstract
     *
     * @return bool
     */
    public function isAbstract(): bool;

    /**
     * Check if the method is static
     *
     * @return bool
     */
    public function isStatic(): bool;

    /**
     * Get the string object to use for reflection applications
     * ($this->instance or null)
     *
     * @return string
     */
    public function getObjectToUse(): string;

    /**
     * Generate a value for the method return type
     *
     * @return string
     */
    public function generateValue(): string;

    /**
     * Generate a values for the method arguments
     *
     * @return string
     */
    public function generateValues(): string;
}
