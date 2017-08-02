<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\CLI;

use PHPUnitGenerator\CLI\CLIInterface\PrinterInterface;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;

/**
 * Class PrinterTrait
 *
 *      A class to print colored messages on output
 *
 * @package PHPUnitGenerator\CLI
 */
class ColoredPrinter implements PrinterInterface
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * ColoredPrinter constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Print an error message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function error(string $message, ... $args)
    {
        $this->printMessage('41', $message, $args);
    }

    /**
     * Print a warning message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function warning(string $message, ... $args)
    {
        $this->printMessage('43', $message, $args);
    }

    /**
     * Print a success message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function success(string $message, ... $args)
    {
        $this->printMessage('42', $message, $args);
    }

    /**
     * Print an info message
     *
     * @param string $message
     * @param array  ...$args
     */
    public function info(string $message, ... $args)
    {
        $this->printMessage('0', $message, $args);
    }

    /**
     * Print a message of the desired type
     *
     * @param string $type
     * @param string $message
     * @param array  $args
     */
    protected function printMessage(string $type, string $message, array $args)
    {
        if ($this->config->getOption(ConfigInterface::OPTION_PRINT) === true) {
            if ($this->config->getOption(ConfigInterface::OPTION_NO_COLOR) === true) {
                echo vsprintf($message, $args) . "\n\n";
            } else {
                echo "\033[{$type}m" . vsprintf($message, $args) . "\033[0m\n\n";
            }
        }
    }
}
