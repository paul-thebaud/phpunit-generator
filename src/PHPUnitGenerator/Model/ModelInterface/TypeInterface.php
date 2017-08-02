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
 * Interface TypeInterface
 *
 *      Specifies possible types for ArgumentModelInterface::getType
 *      or MethodModelInterface::getReturnType as constants
 *
 * @package PHPUnitGenerator\Model\ModelInterface
 */
interface TypeInterface
{
    /*
     * NB: If it is not in those types, it means it is an object
     */

    /**
     * @var string TYPE_MIXED Unknown type (mixed value)
     */
    const TYPE_MIXED = '';

    /**
     * @var string TYPE_BOOL Type boolean
     */
    const TYPE_BOOL = 'bool';

    /**
     * @var string TYPE_INT Type integer
     */
    const TYPE_INT = 'int';

    /**
     * @var string TYPE_FLOAT Type float / double
     */
    const TYPE_FLOAT = 'float';

    /**
     * @var string TYPE_STRING Type string
     */
    const TYPE_STRING = 'string';

    /**
     * @var string TYPE_ARRAY Type array
     */
    const TYPE_ARRAY = 'array';

    /**
     * @var string TYPE_CALLABLE Type callable
     */
    const TYPE_CALLABLE = 'callable';
}
