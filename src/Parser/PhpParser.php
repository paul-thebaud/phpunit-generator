<?php

namespace PhpUnitGen\Parser;

use PhpParser\Error;
use PhpParser\Parser;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Parser\NodeParser\PhpFileNodeParser;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;

/**
 * Class PhpParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpParser implements PhpParserInterface
{
    /**
     * @var Parser $phpParser A parser to parse php code as a string.
     */
    private $phpParser;

    /**
     * @var PhpFileNodeParser $phpFileNodeParser A php file node parser to parse php nodes.
     */
    private $phpFileNodeParser;

    /**
     * PhpFileParser constructor.
     *
     * @param Parser            $phpParser         The php code parser.
     * @param PhpFileNodeParser $phpFileNodeParser The php file node parser.
     */
    public function __construct(
        Parser $phpParser,
        PhpFileNodeParser $phpFileNodeParser
    ) {
        $this->phpParser         = $phpParser;
        $this->phpFileNodeParser = $phpFileNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(string $code): PhpFileModelInterface
    {
        try {
            $nodes = $this->phpParser->parse($code);
        } catch (Error $error) {
            throw new ParsingException("Unable to parse given php code (maybe your code contains errors).");
        }

        $phpFileModel = new PhpFileModel();

        /** @var PhpFileModelInterface $phpFileModel */
        $phpFileModel = $this->phpFileNodeParser->parseSubNodes($nodes, $phpFileModel);

        return $phpFileModel;
    }
}
