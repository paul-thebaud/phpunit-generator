<?php

namespace PhpUnitGen\Parser;

use PhpParser\Error;
use PhpParser\Parser;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Parser\ParserInterface\PhpFileParserInterface;

/**
 * Class PhpFileParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpFileParser implements PhpFileParserInterface
{
    private $phpParser;

    public function __construct(Parser $phpParser)
    {
        $this->phpParser = $phpParser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $code): PhpFileModelInterface
    {
        try {
            $statements = $this->phpParser->parse($code);
        } catch (Error $error) {
            throw new ParsingException("Unable to parse given php code (maybe your code contains errors).");
        }

        return new PhpFileModel();
    }
}
