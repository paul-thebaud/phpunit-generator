<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Generator\GeneratorInterface;

use PHPUnitGenerator\Exception\InvalidTypeException;

/**
 * Interface ValueGeneratorInterface
 *
 *      Specifies which methods will contains a ValueGenerator
 *      A ValueGenerator will generate value for a specified type.
 *
 * @package PHPUnitGenerator\Generator\GeneratorInterface
 */
interface ValueGeneratorInterface
{
    /**
     * Generate a value for a type
     *
     * @param string $type
     *
     * @return string
     *
     * @throws InvalidTypeException
     */
    public static function generateValue(string $type): string;
}
