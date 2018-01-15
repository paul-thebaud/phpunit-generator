<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use Respect\Validation\Validator;

/**
 * Class NamespaceNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class NamespaceNodeParser extends AbstractNodeParser
{
    public function __construct(
        UseNodeParser $useNodeParser,
        InterfaceNodeParser $interfaceNodeParser,
        TraitNodeParser $traitNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\Use_::class]       = $useNodeParser;
        $this->nodeParsers[Node\Stmt\Interface_::class] = $interfaceNodeParser;
        $this->nodeParsers[Node\Stmt\Trait_::class]     = $traitNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\Namespace_  $node   The namespace node to parse.
         * @var PhpFileModelInterface $parent The node which contains this namespace.
         */
        if (Validator::instance(Node\Name::class)->validate($node->name)) {
            $parent->setNamespace($node->name->parts);
        }
        $parent = $this->parseSubNodes($node->stmts, $parent);

        return $parent;
    }
}
