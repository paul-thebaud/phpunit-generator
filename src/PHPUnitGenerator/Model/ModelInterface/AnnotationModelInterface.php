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
 * Interface AnnotationModelInterface
 *
 *      Specifies which methods will contains a AnnotationModel
 *      An AnnotationModel is a representation of a PHPUnit Generator
 *      annotation in method documentation
 *
 * @package PHPUnitGenerator\Model\ModelInterface
 */
interface AnnotationModelInterface
{
    /**
     * @var string ANNOTATION_REGEX The regex to match a PHPUnitGen annotation
     *      in documentation First part is the annotation type, second is
     *      content
     */
    const ANNOTATION_REGEX = '/^PHPUnitGen\\\\([a-zA-Z]+){1}((.|\s)*){1}$/';

    /**
     * @var string TYPE_PHPUNIT_METHOD If the annotation type does not match
     *      "Get" or "Set"
     */
    const TYPE_PHPUNIT_METHOD = 'mixed';

    /**
     * @var string TYPE_GET If the annotation type is "Get"
     */
    const TYPE_GET = 'get';

    /**
     * @var string TYPE_SET If the annotation type is "Set"
     */
    const TYPE_SET = 'set';

    /**
     * Get the annotation type as a string
     * (presents in self constants named TYPE_<type>)
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set the annotation type
     *
     * @param string $type
     *
     * @return AnnotationModelInterface
     */
    public function setType(string $type);

    /**
     * Get the method which has this AnnotationModelInterface
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
     * Get the string to call the method in the test
     *
     * @return string
     */
    public function getCall(): string;
}
