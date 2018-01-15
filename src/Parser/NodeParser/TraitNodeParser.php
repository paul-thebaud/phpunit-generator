<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\TraitModel;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\AttributeNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\MethodNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\TraitNodeParserInterface;

/**
 * Class TraitNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class TraitNodeParser extends AbstractNodeParser implements TraitNodeParserInterface
{
    /**
     * TraitNodeParser constructor.
     *
     * @param MethodNodeParserInterface    $methodNodeParser    The method node parser to use.
     * @param AttributeNodeParserInterface $attributeNodeParser The attribute node parser to use.
     */
    public function __construct(
        MethodNodeParserInterface $methodNodeParser,
        AttributeNodeParserInterface $attributeNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $methodNodeParser;
        $this->nodeParsers[Node\Stmt\Property::class]    = $attributeNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\Trait_      $node   The node to parse.
     * @param PhpFileModelInterface $parent The parent node.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\Trait_ $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        $trait = new TraitModel();
        $trait->setParentNode($parent);
        $trait->setName($node->name);

        $trait = $this->parseSubNodes($node->stmts, $trait);

        $parent->addTrait($trait);

        return $parent;
    }
}
