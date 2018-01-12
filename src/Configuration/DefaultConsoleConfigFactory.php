<?php

namespace PhpUnitGen\Configuration;

/**
 * Class DefaultConsoleConfigFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DefaultConsoleConfigFactory
{
    /**
     * Build a console configuration from a configuration file.
     *
     * @param string $sourceDirectory The source directory.
     * @param string $targetDirectory The target directory.
     *
     * @return ConsoleConfigInterface The created configuration.
     */
    public function invoke(string $sourceDirectory, string $targetDirectory): ConsoleConfigInterface
    {
        $configArray         = require __DIR__ . '/../../config/default.phpunitgen.config.php';
        $configArray['dirs'] = [
            $sourceDirectory => $targetDirectory
        ];

        return new ConsoleConfig($configArray);
    }
}
