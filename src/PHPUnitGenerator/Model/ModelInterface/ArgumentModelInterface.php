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
 * Interface ArgumentModelInterface
 *
 *      Specifies which methods will contains an ArgumentModel
 *      An ArgumentModel is a representation of a PHP argument
 *      for a method
 *
 * @package PHPUnitGenerator\Model\ModelInterface
 */
interface ArgumentModelInterface
{
    /**
     * Get the argument name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the argument type as a string
     * (presents in TypeInterface constants named TYPE_<type>)
     *
     * @see \PHPUnitGenerator\Model\ModelInterface\TypeInterface
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set the argument type
     *
     * @param string $type
     *
     * @return ArgumentModelInterface
     */
    public function setType(string $type);

    /**
     * Get a boolean which tells if the value can be null
     *
     * @return bool
     */
    public function getCanBeNull(): bool;

    /**
     * Set the $canBeNull boolean
     *
     * @param bool $canBeNull
     *
     * @return ArgumentModelInterface
     */
    public function setCanBeNull(bool $canBeNull);

    /**
     * Get the method which uses this argument
     *
     * @return MethodModelInterface
     */
    public function getParentMethod(): MethodModelInterface;

    /*
     **********************************************************************
     *
     * Methods which use properties
     *
     **********************************************************************
     */

    /**
     * Generate a value for the argument type
     *
     * @return string
     */
    public function generateValue(): string;
}
