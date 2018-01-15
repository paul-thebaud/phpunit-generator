<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ClassNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\FunctionNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\GroupUseNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\InterfaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\NamespaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\PhpFileNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\TraitNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\UseNodeParserInterface;
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
class PhpFileNodeParser extends AbstractNodeParser implements PhpFileNodeParserInterface
{
    use UsePreParseTrait;

    /**
     * PhpFileNodeParser constructor.
     *
     * @param NamespaceNodeParserInterface $namespaceNodeParser The namespace node parser to use.
     * @param UseNodeParserInterface       $useNodeParser       The use node parser to use.
     * @param GroupUseNodeParserInterface  $groupUseNodeParser  The group use node parser to use.
     * @param FunctionNodeParserInterface  $functionNodeParser  The function node parser to use.
     * @param ClassNodeParserInterface     $classNodeParser     The class node parser to use.
     * @param TraitNodeParserInterface     $traitNodeParser     The trait node parser to use.
     * @param InterfaceNodeParserInterface $interfaceNodeParser The interface node parser to use.
     */
    public function __construct(
        NamespaceNodeParserInterface $namespaceNodeParser,
        UseNodeParserInterface $useNodeParser,
        GroupUseNodeParserInterface $groupUseNodeParser,
        FunctionNodeParserInterface $functionNodeParser,
        ClassNodeParserInterface $classNodeParser,
        TraitNodeParserInterface $traitNodeParser,
        InterfaceNodeParserInterface $interfaceNodeParser
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
     * A php file is the root of each nodes, so it does not have a invoke method.
     */
}
