<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ParameterModel;

/**
 * Class ParameterNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ParameterNodeParser extends AbstractNodeParser
{
    /**
     * @var TypeNodeParser $typeNodeParser The type node parser.
     */
    protected $typeNodeParser;

    /**
     * @var ValueNodeParser $valueNodeParser The value node parser.
     */
    protected $valueNodeParser;

    /**
     * AttributeNodeParser constructor.
     *
     * @param TypeNodeParser  $typeNodeParser  The type node parser.
     * @param ValueNodeParser $valueNodeParser The value node parser.
     */
    public function __construct(TypeNodeParser $typeNodeParser, ValueNodeParser $valueNodeParser)
    {
        $this->typeNodeParser  = $typeNodeParser;
        $this->valueNodeParser = $valueNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Param             $node   The node to parse.
     * @param FunctionModelInterface $parent The parent node.
     *
     * @return FunctionModelInterface The updated parent.
     */
    public function invoke(Node\Param $node, FunctionModelInterface $parent): FunctionModelInterface
    {
        $parameter = new ParameterModel();
        $parameter->setParentNode($parent);
        $parameter->setName($node->name);
        $parameter->setIsVariadic($node->variadic);

        if ($node->type !== null) {
            $parameter = $this->typeNodeParser->invoke($node->type, $parameter);
        }
        if ($node->default !== null) {
            $parameter = $this->valueNodeParser->invoke($node->default, $parameter);
        }

        $parent->addParameter($parameter);

        return $parent;
    }
}
