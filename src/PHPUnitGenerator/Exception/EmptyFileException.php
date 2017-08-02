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
 * Class EmptyFileException
 *
 *      If the file to parse does not contain a class, abstract class, trait or
 *      interface
 *
 * @package PHPUnitGenerator\Exception
 */
class EmptyFileException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @var string TEXT The exception description text
     */
    const TEXT = 'The file you provided is empty (no class, interface or trait statement found).';
}
