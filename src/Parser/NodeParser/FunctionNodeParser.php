<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParserUtil\DocumentationTrait;

/**
 * Class FunctionNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FunctionNodeParser extends AbstractNodeParser
{
    use DocumentationTrait;

    /**
     * @var ParameterNodeParser $parameterNodeParser The parameter node parser.
     */
    protected $parameterNodeParser;

    /**
     * FunctionNodeParser constructor.
     *
     * @param ParameterNodeParser $parameterNodeParser The parameter node parser.
     */
    public function __construct(ParameterNodeParser $parameterNodeParser)
    {
        $this->parameterNodeParser = $parameterNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\Function_   $node   The node to parse.
     * @param PhpFileModelInterface $parent The parent node.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\Function_ $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        $function = new FunctionModel();
        $function->setParentNode($parent);
        $function->setName($node->name);
        $function->setDocumentation($this->getDocumentation($node));

        foreach ($node->getParams() as $param) {
            $function = $this->parameterNodeParser->invoke($param, $function);
        }

        $parent->addFunction($function);

        return $parent;
    }
}
