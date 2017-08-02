<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\CLI\CLIInterface;

/**
 * Interface PrinterInterface
 *
 *      Specifies which methods will have a Printer
 *      A Printer will display message in output
 *
 * @package PHPUnitGenerator\CLI\CLIInterface
 */
interface PrinterInterface
{
    /**
     * Print an error message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function error(string $message, ... $args);

    /**
     * Print a warning message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function warning(string $message, ... $args);

    /**
     * Print a success message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function success(string $message, ... $args);

    /**
     * Print an info message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function info(string $message, ... $args);
}
