<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Config\ConfigInterface;

/**
 * Interface ConfigInterface
 *
 *      Specifies possible options for PHPUnit Generator
 *      A Config will allow to have specific options in other class
 *
 * @package PHPUnitGenerator\Config\ConfigInterface
 */
interface ConfigInterface
{
    /**
     * @var string OPTION_FILE Tells the path to generate tests for is a file
     *      and not a directory [bool]
     */
    const OPTION_FILE = 'file';

    /**
     * @var string OPTION_NO_COLOR Tells if the output message should be
     *      written without colors
     */
    const OPTION_NO_COLOR = 'no-color';

    /*
     * Use the following options when you use TestGenerator::writeFile()
     */

    /**
     * @var string OPTION_OVERWRITE Tells if existing files should be
     *      overwritten [bool]
     */
    const OPTION_OVERWRITE = 'overwrite';

    /**
     * @var string OPTION_INTERFACE Tells if interface tests should be rendered
     *      too [bool]
     */
    const OPTION_INTERFACE = 'interface';

    /**
     * @var string OPTION_PRINT Tells if tests generation advancement should be
     *      written on output or not [bool]
     */
    const OPTION_PRINT = 'print';

    /**
     * @var string OPTION_AUTO Tells if getter and setter tests should be
     *      create even if there is no annotation [bool]
     */
    const OPTION_AUTO = 'auto';

    /**
     * @var string OPTION_DOC_AUTHOR The author PHPDoc tag content
     */
    const OPTION_DOC_AUTHOR = 'doc-author';

    /**
     * @var string OPTION_DOC_COPYRIGHT The copyright PHPDoc tag content
     */
    const OPTION_DOC_COPYRIGHT = 'doc-copyright';

    /**
     * @var string OPTION_DOC_LICENCE The licence PHPDoc tag content
     */
    const OPTION_DOC_LICENCE = 'doc-licence';

    /**
     * @var string OPTION_DOC_SINCE The since PHPDoc tag content
     */
    const OPTION_DOC_SINCE = 'doc-since';

    /*
     * Use the following options when you use TestGenerator::writeDir()
     */

    /**
     * @var string OPTION_IGNORE Tells if error that can be ignore should be
     *      ignored [bool]
     */
    const OPTION_IGNORE = 'ignore';

    /**
     * @var string OPTION_INCLUDE The regex that files need to match [string]
     */
    const OPTION_INCLUDE = 'include';

    /**
     * @var string OPTION_EXCLUDE The regex that files must not match [string]
     */
    const OPTION_EXCLUDE = 'exclude';

    /**
     * ConfigInterface constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = []);

    /**
     * Get all options
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Get one option by $key, returns $default if option does not exists
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption(string $key, $default = null);
}
