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
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserUtil\UsePreParseTrait;

/**
 * Class PhpFileNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpFileNodeParser extends AbstractNodeParser
{
    use UsePreParseTrait;

    /**
     * PhpFileNodeParser constructor.
     *
     * @param NamespaceNodeParser $namespaceNodeParser The namespace node parser to use.
     * @param UseNodeParser       $useNodeParser       The use node parser to use.
     * @param GroupUseNodeParser  $groupUseNodeParser  The group use node parser to use.
     * @param FunctionNodeParser  $functionNodeParser  The function node parser to use.
     * @param ClassNodeParser     $classNodeParser     The class node parser to use.
     * @param TraitNodeParser     $traitNodeParser     The trait node parser to use.
     * @param InterfaceNodeParser $interfaceNodeParser The interface node parser to use.
     */
    public function __construct(
        NamespaceNodeParser $namespaceNodeParser,
        UseNodeParser $useNodeParser,
        GroupUseNodeParser $groupUseNodeParser,
        FunctionNodeParser $functionNodeParser,
        ClassNodeParser $classNodeParser,
        TraitNodeParser $traitNodeParser,
        InterfaceNodeParser $interfaceNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\Namespace_::class] = $namespaceNodeParser;
        $this->nodeParsers[Node\Stmt\Function_::class]  = $functionNodeParser;
        $this->nodeParsers[Node\Stmt\Class_::class]     = $classNodeParser;
        $this->nodeParsers[Node\Stmt\Trait_::class]     = $traitNodeParser;
        $this->nodeParsers[Node\Stmt\Interface_::class] = $interfaceNodeParser;

        $this->useNodeParser      = $useNodeParser;
        $this->groupUseNodeParser = $groupUseNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke($node, NodeInterface $parent): void
    {
        throw new Exception('A PhpFile is the root of parsing, so only sub-statements can be parsed.');
    }
}
