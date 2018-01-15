<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ClassNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\FunctionNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\InterfaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\NamespaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\TraitNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\UseNodeParserInterface;
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
class NamespaceNodeParser extends AbstractNodeParser implements NamespaceNodeParserInterface
{
    use UsePreParseTrait;

    /**
     * NamespaceNodeParser constructor.
     *
     * @param UseNodeParserInterface       $useNodeParser       The use node parser to use.
     * @param FunctionNodeParserInterface  $functionNodeParser  The function node parser to use.
     * @param ClassNodeParserInterface     $classNodeParser     The class node parser to use.
     * @param TraitNodeParserInterface     $traitNodeParser     The trait node parser to use.
     * @param InterfaceNodeParserInterface $interfaceNodeParser The interface node parser to use.
     */
    public function __construct(
        UseNodeParserInterface $useNodeParser,
        FunctionNodeParserInterface $functionNodeParser,
        ClassNodeParserInterface $classNodeParser,
        TraitNodeParserInterface $traitNodeParser,
        InterfaceNodeParserInterface $interfaceNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\Use_::class]       = $useNodeParser;
        $this->nodeParsers[Node\Stmt\Function_::class]  = $functionNodeParser;
        $this->nodeParsers[Node\Stmt\Class_::class]     = $classNodeParser;
        $this->nodeParsers[Node\Stmt\Trait_::class]     = $traitNodeParser;
        $this->nodeParsers[Node\Stmt\Interface_::class] = $interfaceNodeParser;

        $this->useNodeParser = $useNodeParser;
    }

    /**
     * {@inheritdoc }
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