<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameHelper;

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
     * @var DocumentationNodeParser $documentationNodeParser The documentation node parser to use.
     */
    private $documentationNodeParser;

    /**
     * ClassNodeParser constructor.
     *
     * @param MethodNodeParser        $methodNodeParser        The method node parser to use.
     * @param AttributeNodeParser     $attributeNodeParser     The attribute node parser to use.
     * @param DocumentationNodeParser $documentationNodeParser The documentation node parser to use.
     */
    public function __construct(
        MethodNodeParser $methodNodeParser,
        AttributeNodeParser $attributeNodeParser,
        DocumentationNodeParser $documentationNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $methodNodeParser;
        $this->nodeParsers[Node\Stmt\Property::class]    = $attributeNodeParser;
        $this->documentationNodeParser                   = $documentationNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     *
     * @throws AnnotationParseException If an annotation can not be parsed.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Stmt\Class_ || ! $parent instanceof PhpFileModelInterface) {
            throw new Exception('ClassNodeParser is made to parse a class node');
        }
        $class = new ClassModel();
        $class->setParentNode($parent);
        $class->setName(ClassLikeNameHelper::getName($node));
        $class->setIsAbstract($node->isAbstract());
        $class->setIsFinal($node->isFinal());
        $parent->addConcreteUse($parent->getFullNameFor($class->getName()), $class->getName());

        if (($documentation = $node->getDocComment()) !== null) {
            $this->documentationNodeParser->invoke($documentation, $class);
        }

        $this->parseSubNodes($node->stmts, $class);

        $parent->addClass($class);
    }
}
