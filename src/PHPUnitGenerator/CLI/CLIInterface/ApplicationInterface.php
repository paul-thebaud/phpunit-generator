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

use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;

/**
 * Interface ApplicationInterface
 *
 *      Specifies which methods will contains an Application
 *      An Application will allow to run PHPUnit Generator TestGenerator
 *      with the CLI and multiple options
 *
 * @package PHPUnitGenerator\CLI\CLIInterface
 */
interface ApplicationInterface
{
    /**
     * @var string OPTION_REGEX A regex to match option and get their name / value
     */
    const OPTION_REGEX = '/^--([a-zA-Z\-]*){1}(=(.*){1})?$/';

    /**
     * Get the application Printer
     *
     * @return PrinterInterface
     */
    public static function getPrinter(): PrinterInterface;

    /**
     * Get the application Config
     *
     * @return ConfigInterface
     */
    public static function getConfig(): ConfigInterface;

    /**
     * Run the PHPUnit Generator CLI application with arguments
     *
     * @param array $arguments
     */
    public static function run(array $arguments = []);
}
