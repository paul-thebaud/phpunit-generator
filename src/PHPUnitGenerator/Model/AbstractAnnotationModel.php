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

use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Class AbstractAnnotationModel
 *
 *      An implementation of AnnotationModelInterface
 *
 * @package PHPUnitGenerator\Model
 */
abstract class AbstractAnnotationModel implements AnnotationModelInterface
{
    /**
     * @var string $type The annotation type
     */
    protected $type = self::TYPE_PHPUNIT_METHOD;

    /**
     * @var MethodModelInterface The method which contains this annotation
     */
    protected $method;

    /**
     * AnnotationBaseModel constructor.
     *
     * @param MethodModelInterface $methodModel
     */
    public function __construct(
        MethodModelInterface $methodModel
    ) {
        $this->method = $methodModel;
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
    public function getParentMethod(): MethodModelInterface
    {
        return $this->method;
    }
}
