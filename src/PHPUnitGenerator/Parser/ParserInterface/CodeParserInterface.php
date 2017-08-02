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

use PHPUnitGenerator\Exception\EmptyFileException;
use PHPUnitGenerator\Exception\InvalidCodeException;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;

/**
 * Interface CodeParserInterface
 *
 *      Specifies which methods will contains a CodeParser
 *      A CodeParser will convert PHP code in ClassModel (with methods ...)
 *
 * @package PHPUnitGenerator\Parser\ParserInterface
 */
interface CodeParserInterface
{
    /**
     * Parse PHP code to construct a ClassModelInterface
     *
     * @param string $code
     *
     * @return ClassModelInterface
     *
     * @throws InvalidCodeException
     * @throws EmptyFileException
     */
    public function parse(string $code): ClassModelInterface;
}
