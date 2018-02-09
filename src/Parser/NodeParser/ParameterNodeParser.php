<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ParameterModel;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

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
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Param || ! $parent instanceof FunctionModelInterface) {
            throw new Exception('ParameterNodeParser is made to parse a function parameter node');
        }

        $parameter = new ParameterModel();
        $parameter->setParentNode($parent);
        $parameter->setName($node->name);
        $parameter->setIsVariadic($node->variadic);

        if ($node->type !== null) {
            $this->typeNodeParser->invoke($node->type, $parameter);
        }
        if ($node->default !== null) {
            $this->valueNodeParser->invoke($node->default, $parameter);
        }

        $parent->addParameter($parameter);
    }
}
