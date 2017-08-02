<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Generator;

use PHPUnitGenerator\Exception\InvalidTypeException;
use PHPUnitGenerator\Generator\GeneratorInterface\ValueGeneratorInterface;
use PHPUnitGenerator\Model\ModelInterface\TypeInterface;

/**
 * Class FixedValueGenerator
 *
 *      An implementation of ValueGeneratorInterface to generate fixed values
 *
 * @package PHPUnitGenerator\Generator
 */
class FixedValueGenerator implements ValueGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public static function generateValue(string $type): string
    {
        switch ($type) {
            case TypeInterface::TYPE_BOOL:
                return 'true';
            case TypeInterface::TYPE_INT:
                return '1';
            case TypeInterface::TYPE_FLOAT:
                return '1.5';
            case TypeInterface::TYPE_MIXED:
            case TypeInterface::TYPE_STRING:
                return '\'A simple string\'';
            case TypeInterface::TYPE_ARRAY:
                return '[\'a\', \'simple\', \'array\']';
            case TypeInterface::TYPE_CALLABLE:
                throw new InvalidTypeException(
                    sprintf(
                        InvalidTypeException::TEXT,
                        $type
                    )
                );
            default:
                return '$this->createMock(\\' . $type . '::class)';
        }
    }
}
