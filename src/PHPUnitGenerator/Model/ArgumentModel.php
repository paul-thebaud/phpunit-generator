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

use PHPUnitGenerator\Exception\InvalidTypeException;
use PHPUnitGenerator\Generator\FixedValueGenerator;
use PHPUnitGenerator\Model\ModelInterface\ArgumentModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Model\ModelInterface\TypeInterface;

/**
 * Class ArgumentModel
 *
 *      An implementation of ArgumentModelInterface
 *
 * @package PHPUnitGenerator\Model
 */
class ArgumentModel implements ArgumentModelInterface
{
    /**
     * @var string $name The argument name
     */
    private $name;

    /**
     * @var string $type The argument type
     */
    private $type = TypeInterface::TYPE_MIXED;

    /**
     * @var bool $nullable Tells if the method return value can be null
     */
    private $nullable = false;

    /**
     * @var MethodModelInterface $method The argument parent method
     */
    private $method;

    /**
     * ArgumentModel constructor.
     *
     * @param MethodModelInterface $methodModel
     * @param string               $name
     */
    public function __construct(MethodModelInterface $methodModel, string $name)
    {
        $this->method = $methodModel;
        $this->name = $name;
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
    public function getNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * {@inheritdoc}
     */
    public function setNullable(bool $nullable)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParentMethod(): MethodModelInterface
    {
        return $this->method;
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
    public function generateValue(): string
    {
        try {
            return FixedValueGenerator::generateValue($this->getType());
        } catch (InvalidTypeException $exception) {
            return '/** @todo: A callable value */';
        }
    }
}
