<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParserUtil\UsePreParseTrait;
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
    use UsePreParseTrait;

    /**
     * NamespaceNodeParser constructor.
     *
     * @param UseNodeParser       $useNodeParser       The use node parser to use.
     * @param GroupUseNodeParser  $groupUseNodeParser  The group use node parser to use.
     * @param FunctionNodeParser  $functionNodeParser  The function node parser to use.
     * @param ClassNodeParser     $classNodeParser     The class node parser to use.
     * @param TraitNodeParser     $traitNodeParser     The trait node parser to use.
     * @param InterfaceNodeParser $interfaceNodeParser The interface node parser to use.
     */
    public function __construct(
        UseNodeParser $useNodeParser,
        GroupUseNodeParser $groupUseNodeParser,
        FunctionNodeParser $functionNodeParser,
        ClassNodeParser $classNodeParser,
        TraitNodeParser $traitNodeParser,
        InterfaceNodeParser $interfaceNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\Function_::class]  = $functionNodeParser;
        $this->nodeParsers[Node\Stmt\Class_::class]     = $classNodeParser;
        $this->nodeParsers[Node\Stmt\Trait_::class]     = $traitNodeParser;
        $this->nodeParsers[Node\Stmt\Interface_::class] = $interfaceNodeParser;

        $this->useNodeParser      = $useNodeParser;
        $this->groupUseNodeParser = $groupUseNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\Namespace_  $node   The node to parse.
     * @param PhpFileModelInterface $parent The parent node.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\Namespace_ $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        if (Validator::instance(Node\Name::class)->validate($node->name)) {
            $parent->setNamespace($node->name->parts);
        }

        $parent = $this->preParseUses($node->stmts, $parent);

        return $this->parseSubNodes($node->stmts, $parent);
    }
}
