<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;

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
    public function invokeDir(string $sourceDirectory, string $targetDirectory): ConsoleConfigInterface
    {
        $configArray         = require __DIR__ . '/../../config/default.phpunitgen.config.php';
        $configArray['dirs'] = [
            $sourceDirectory => $targetDirectory
        ];

        return new ConsoleConfig($configArray);
    }

    /**
     * Build a console configuration from a configuration file.
     *
     * @param string $sourceFile The source file.
     * @param string $targetFile The target file.
     *
     * @return ConsoleConfigInterface The created configuration.
     */
    public function invokeFile(string $sourceFile, string $targetFile): ConsoleConfigInterface
    {
        $configArray          = require __DIR__ . '/../../config/default.phpunitgen.config.php';
        $configArray['files'] = [
            $sourceFile => $targetFile
        ];

        return new ConsoleConfig($configArray);
    }
}
