<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Configuration\ConfigurationInterface;

/**
 * Interface ConsoleConfigInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ConsoleConfigInterface extends ConfigInterface
{
    /**
     * @return bool True if existing files need to be overwritten by new ones.
     */
    public function hasOverwrite(): bool;

    /**
     * @return bool True if existing files need to be backed up before erase them.
     */
    public function hasBackup(): bool;

    /**
     * @return bool True if errors need to be ignored.
     */
    public function hasIgnore(): bool;

    /**
     * @return string A regex to describe file that should be parsed.
     */
    public function getIncludeRegex(): ?string;

    /**
     * @return string A regex to describe file that should not be parsed.
     */
    public function getExcludeRegex(): ?string;

    /**
     * Get directories to use.
     * Array keys are source directories, and array values are tests directories.
     *
     * @return string[] The directories to parse.
     */
    public function getDirectories(): array;

    /**
     * Get files to use.
     * Array keys are source files, and array values are tests files.
     *
     * @return string[] The files to parse.
     */
    public function getFiles(): array;
}
