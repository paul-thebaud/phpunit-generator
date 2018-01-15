<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ParameterModel;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ParameterNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\TypeNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ValueNodeParserInterface;

/**
 * Class ParameterNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ParameterNodeParser extends AbstractNodeParser implements ParameterNodeParserInterface
{
    /**
     * @var TypeNodeParserInterface $typeNodeParser The type node parser.
     */
    protected $typeNodeParser;

    /**
     * @var ValueNodeParserInterface $valueNodeParser The value node parser.
     */
    protected $valueNodeParser;

    /**
     * AttributeNodeParser constructor.
     *
     * @param TypeNodeParserInterface  $typeNodeParser  The type node parser.
     * @param ValueNodeParserInterface $valueNodeParser The value node parser.
     */
    public function __construct(TypeNodeParserInterface $typeNodeParser, ValueNodeParserInterface $valueNodeParser)
    {
        $this->typeNodeParser  = $typeNodeParser;
        $this->valueNodeParser = $valueNodeParser;
    }

    /**
     * {@inheritdoc}
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
