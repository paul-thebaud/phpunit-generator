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
 * Class InvalidRegexException
 *
 *      If the given include or exclude regex is invalid
 *
 * @package PHPUnitGenerator\Exception
 */
class InvalidRegexException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @var string TEXT The exception description text
     */
    const TEXT = 'The include / exclude regex is invalid.';
}
