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

use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Class AnnotationBaseModel
 *
 *      An implementation of AnnotationModelInterface to support following
 *      annotations:
 *      "@PHPUnitGen\<phpunit_method>(<expected>:{<arguments>})"
 *      "@PHPUnitGen\<phpunit_method>({<arguments>})"
 *      "@PHPUnitGen\<phpunit_method>()"
 *
 * @package PHPUnitGenerator\Model
 */
class AnnotationBaseModel extends AbstractAnnotationModel
{
    /**
     * @var string CONTENT_REGEX A regex to analyse annotation content
     */
    const CONTENT_REGEX = '/\(((.|\s)*?)?(:\{((.|\s)*?){1}\})?\)/';

    /**
     * @var string $methodName The PHPUnit method to use
     */
    private $methodName;

    /**
     * @var string|null $expected The expected value as a string
     */
    private $expected = null;

    /**
     * @var string|null $arguments The arguments to use as a string
     */
    private $arguments = null;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $methodName,
        string $content,
        MethodModelInterface $methodModel
    ) {
        parent::__construct($methodModel);

        $this->methodName = lcfirst($methodName);

        if (preg_match(self::CONTENT_REGEX, $content, $matches) > 0) {
            $this->expected = $matches[1] ?? null;
            $this->arguments = $matches[4] ?? null;
        }
    }

    /**
     * Get the PHPUnit method to use
     *
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * Get the expected value or generate one
     *
     * @return string
     */
    public function getExpected(): string
    {
        return $this->expected !== null ? ($this->expected . ', ') : '';
    }

    /**
     * Get the arguments to use or generate them
     *
     * @return string
     */
    public function getArguments(): string
    {
        return $this->arguments ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getCall(): string
    {
        if ($this->getParentMethod()->isPublic()) {
            if ($this->getParentMethod()->isStatic()) {
                return sprintf(
                    '%s::%s(',
                    $this->getParentMethod()->getParentClass()->getName(),
                    $this->getParentMethod()->getName()
                );
            }
            return sprintf(
                '$this->instance->%s(',
                $this->getParentMethod()->getName()
            );
        }
        return sprintf('$method->invoke(%s', $this->getParentMethod()->getObjectToUse())
            . ($this->arguments === null ? '' : ', ');
    }
}
