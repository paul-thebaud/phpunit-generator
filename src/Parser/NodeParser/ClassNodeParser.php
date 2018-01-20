<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameTrait;

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
    use ClassLikeNameTrait;

    /**
     * ClassNodeParser constructor.
     *
     * @param MethodNodeParser    $methodNodeParser    The method node parser to use.
     * @param AttributeNodeParser $attributeNodeParser The attribute node parser to use.
     */
    public function __construct(
        MethodNodeParser $methodNodeParser,
        AttributeNodeParser $attributeNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $methodNodeParser;
        $this->nodeParsers[Node\Stmt\Property::class]    = $attributeNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\Class_      $node   The node to parse.
     * @param PhpFileModelInterface $parent The parent node.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\Class_ $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        $class = new ClassModel();
        $class->setParentNode($parent);
        $class->setName($this->getName($node));
        $class->setIsAbstract($node->isAbstract());
        $class->setIsFinal($node->isFinal());
        $parent->addConcreteUse($parent->getFullNameFor($class->getName()), $class->getName());

        $class = $this->parseSubNodes($node->stmts, $class);

        $parent->addClass($class);

        return $parent;
    }
}
