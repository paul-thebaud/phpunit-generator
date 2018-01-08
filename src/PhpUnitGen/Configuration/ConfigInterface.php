<?php

namespace PhpUnitGen\Configuration;

/**
 * Interface ConfigInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ConfigInterface
{
    /**
     * @return bool True if the output needs to be quiet.
     */
    public function hasQuiet(): bool;

    /**
     * @return bool True if existing files need to be overwritten.
     */
    public function hasOverwrite(): bool;

    /**
     * @return bool True if interfaces need to be parsed too.
     */
    public function hasInterfaceParsing(): bool;

    /**
     * @return bool True if errors need to be ignored.
     */
    public function hasIgnore(): bool;

    /**
     * @return string A regex to describe file to include.
     */
    public function getIncludeRegex(): string;

    /**
     * @return string A regex to describe file to exclude.
     */
    public function getExcludeRegex(): string;

    /**
     * Get directories to use.
     * Array keys are source directories, and array values are tests directories.
     *
     * @return array The directories to parse.
     */
    public function getDirectories(): array;
}
