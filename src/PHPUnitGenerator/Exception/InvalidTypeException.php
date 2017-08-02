<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Exception;

use PHPUnitGenerator\Exception\ExceptionInterface\ExceptionInterface;

/**
 * Class InvalidTypeException
 *
 *      If the value to generate can not be generated
 *
 * @package PHPUnitGenerator\Exception
 */
class InvalidTypeException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @var string TEXT The exception description text
     */
    const TEXT = 'ValueGenerator cannot generate value for type "%s"';
}
