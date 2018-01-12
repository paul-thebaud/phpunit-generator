<?php

namespace PhpUnitGen\Configuration\ConfigurationInterface;

/**
 * Interface ConsoleConfigFactoryInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ConsoleConfigFactoryInterface
{
    /**
     * Build a console configuration from a configuration file.
     *
     * @param string $configPath The configuration file path.
     *
     * @return ConsoleConfigInterface The created configuration.
     */
    public function invoke(string $configPath): ConsoleConfigInterface;

    /**
     * Build a console configuration from a configuration file.
     *
     * @param string $configPath The configuration file path.
     * @param string $sourceFile The source file.
     * @param string $targetFile The target file.
     *
     * @return ConsoleConfigInterface The created configuration.
     */
    public function invokeOneFile(string $configPath, string $sourceFile, string $targetFile): ConsoleConfigInterface;
}
