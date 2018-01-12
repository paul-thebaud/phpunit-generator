<?php

namespace PhpUnitGen\Parser\ParserInterface;

use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\ModelInterface\DirectoryModelInterface;

/**
 * Interface DirectoryParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface DirectoryParserInterface
{
    /**
     * Parse a source directory to find and construct files.
     *
     * @param string $sourceDirectory The source directory.
     * @param string $targetDirectory The target directory.
     *
     * @return DirectoryModelInterface A directory model containing php files to parse.
     *
     * @throws ParsingException If there is an error during parsing.
     */
    public function parse(string $sourceDirectory, string $targetDirectory): DirectoryModelInterface;
}
