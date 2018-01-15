<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\TraitModel;

/**
 * Class TraitNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class TraitNodeParser extends AbstractNodeParser
{
    /**
     * InterfaceNodeParser constructor.
     *
     * @param MethodNodeParser    $methodNodeParser    The method node parser.
     * @param AttributeNodeParser $attributeNodeParser The attribute node parser.
     */
    public function __construct(MethodNodeParser $methodNodeParser, AttributeNodeParser $attributeNodeParser)
    {
        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $methodNodeParser;
        $this->nodeParsers[Node\Stmt\Property::class]    = $attributeNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\Trait_      $node   The namespace node to parse.
         * @var PhpFileModelInterface $parent The node which contains this namespace.
         */
        $trait = new TraitModel();
        $trait->setName($node->name);

        $trait = $this->parseSubNodes($node->stmts, $trait);

        $parent->addTrait($trait);

        return $parent;
    }
}
