<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Parser\ParserInterface;

use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Interface DocumentationParserInterface
 *
 *      Specifies which methods will contains a DocumentationParser
 *      A DocumentationParser will convert a MethodModelInterface documentation
 *      into an AnnotationModelInterface array
 *
 * @package PHPUnitGenerator\Parser\ParserInterface
 */
interface DocumentationParserInterface
{
    /**
     * Parse a MethodModelInterface documentation to create a
     * AnnotationModelInterface array
     *
     * @param MethodModelInterface $methodModel
     *
     * @return AnnotationModelInterface[]
     */
    public function parse(MethodModelInterface $methodModel): array;
}
