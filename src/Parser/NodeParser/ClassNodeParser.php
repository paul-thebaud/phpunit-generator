<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class ClassNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ClassNodeParser extends AbstractNodeParser
{
    /**
     * InterfaceNodeParser constructor.
     *
     * @param FunctionNodeParser  $functionNodeParser  The function node parser.
     * @param AttributeNodeParser $attributeNodeParser The attribute node parser.
     */
    public function __construct(FunctionNodeParser $functionNodeParser, AttributeNodeParser $attributeNodeParser)
    {
        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $functionNodeParser;
        $this->nodeParsers[Node\Stmt\Property::class]    = $attributeNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\Class_      $node   The class node to parse.
         * @var PhpFileModelInterface $parent The node which contains this namespace.
         */
        $class = new ClassModel();
        $class->setName($node->name);
        $class->setIsAbstract($node->isAbstract());
        $class->setIsFinal($node->isFinal());

        $class = $this->parseSubNodes($node->stmts, $class);

        $parent->addClass($class);

        return $parent;
    }
}
