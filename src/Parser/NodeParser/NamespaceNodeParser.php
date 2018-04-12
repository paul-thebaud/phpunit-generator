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
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserUtil\UsePreParseTrait;

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
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Stmt\Namespace_ || ! $parent instanceof PhpFileModelInterface) {
            throw new Exception('NamespaceNodeParser is made to parse a namespace node');
        }

        if ($node->name instanceof Node\Name) {
            $parent->setNamespace($node->name->parts);
        }

        $this->preParseUses($node->stmts, $parent);

        $this->parseSubNodes($node->stmts, $parent);
    }
}
