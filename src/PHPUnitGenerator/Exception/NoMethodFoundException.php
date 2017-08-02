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
 * Class NoMethodFoundException
 *
 *      If the class to test has no method
 *
 * @package PHPUnitGenerator\Exception
 */
class NoMethodFoundException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @var string TEXT The exception description text
     */
    const TEXT = 'The class "%s" does not contains any method to test.';
}
