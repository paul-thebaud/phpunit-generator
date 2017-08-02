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

use PHPUnitGenerator\CLI\CLIInterface\ApplicationInterface;
use PHPUnitGenerator\CLI\CLIInterface\PrinterInterface;
use PHPUnitGenerator\Config\Config;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Exception\ExceptionInterface\ExceptionInterface;
use PHPUnitGenerator\Generator\GeneratorInterface\TestGeneratorInterface;
use PHPUnitGenerator\Generator\TestGenerator;

/**
 * Class Application
 *
 *      A class to use PHPUnit Generator with the CLI
 *
 * @package PHPUnitGenerator\CLI
 */
class Application implements ApplicationInterface
{
    /**
     * @var PrinterInterface $printer
     */
    protected static $printer;

    /**
     * @var ConfigInterface $config
     */
    protected static $config;

    /**
     * @var float The application start time
     */
    protected static $startTime = 0;

    /**
     * @var int The number of generated tests file
     */
    protected static $generatedFilesCount = 0;

    /**
     * {@inheritdoc}
     */
    public static function getPrinter(): PrinterInterface
    {
        if (self::$printer) {
            return self::$printer;
        }
        return (self::$printer = new ColoredPrinter(self::getConfig()));
    }

    /**
     * {@inheritdoc}
     */
    public static function getConfig(): ConfigInterface
    {
        if (self::$config) {
            return self::$config;
        }
        return (self::$config = new Config());
    }

    /**
     * {@inheritdoc}
     */
    public static function run(array $arguments = [])
    {
        // Parse options
        self::$config = self::parseOptions($arguments);

        // Start the application
        self::start();

        // Check arguments
        $in = $arguments[1] ?? '';
        if (empty($in)) {
            self::getPrinter()->error('The source path is empty');
            die(-1);
        }
        $out = $arguments[2] ?? '';
        if (empty($out)) {
            self::getPrinter()->error('The target path is empty');
            die(-1);
        }

        // Get tests generator
        $testGenerator = self::getGenerator(self::getConfig());
        // And generate tests
        try {
            if (self::getConfig()->getOption(ConfigInterface::OPTION_FILE) === true) {
                self::$generatedFilesCount = $testGenerator->writeFile($in, $out);
            } else {
                self::$generatedFilesCount = $testGenerator->writeDir($in, $out);
            }
        } catch (ExceptionInterface $exception) {
            self::getPrinter()->error(
                "An error occurred during tests creation:\n\n\t%s",
                $exception->getMessage()
            );
            die(-1);
        }

        self::end();
    }

    /*
     **********************************************************************
     *
     * Methods which use properties
     *
     **********************************************************************
     */

    /**
     * A method to call when the application start
     */
    protected static function start()
    {
        // Save the start time
        self::$startTime = microtime(true);
        // Print version
        self::getPrinter()->info('PHPUnit Generator, version 1.0.0, by Paul Thébaud');
    }

    /**
     * A method to call when the application close
     */
    protected static function end()
    {
        // Print results (generated files count, memory usage and execution time)
        self::getPrinter()->info(
            "Finishing generating tests\n\t"
            . "Tests file generated: %d\n\t"
            . "Memory usage: %s\n\t"
            . "Excution time: %s",
            self::$generatedFilesCount,
            number_format(memory_get_usage() / 1024, 2, '.', '') . 'KB',
            number_format((microtime(true) - self::$startTime), 2, '.', '') . 'ms'
        );

        exit(1);
    }

    /**
     * Parse an arguments array to get an options array
     *
     * @param array $arguments
     *
     * @return ConfigInterface
     */
    protected static function parseOptions(array $arguments = []): ConfigInterface
    {
        $options = [];
        for ($i = 3; $i < count($arguments); $i++) {
            if (preg_match(ApplicationInterface::OPTION_REGEX, $arguments[$i], $matches) > 0) {
                $options[$matches[1]] = $matches[3] ?? true;
            }
        }
        $options[ConfigInterface::OPTION_PRINT] = true;

        return new Config($options);
    }

    /**
     * Construct a new instance of TestGenerator
     *
     * @param ConfigInterface $config
     *
     * @return TestGeneratorInterface
     */
    protected static function getGenerator(ConfigInterface $config): TestGeneratorInterface
    {
        return new TestGenerator($config);
    }
}
