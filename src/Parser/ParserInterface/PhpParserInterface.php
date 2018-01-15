<?php

namespace PhpUnitGen\Parser\ParserInterface;

use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Interface PhpParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface PhpParserInterface
{
    /**
     * Parse php code to build a php file model.
     *
     * @param string $code The code to parse.
     *
     * @return PhpFileModelInterface The created php file model.
     *
     * @throws ParsingException If there is an error during parsing.
     */
    public function invoke(string $code): PhpFileModelInterface;
}
