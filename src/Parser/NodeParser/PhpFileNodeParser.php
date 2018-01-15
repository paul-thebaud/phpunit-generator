<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;

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
    public function __construct(
        NamespaceNodeParser $namespaceNodeParser,
        UseNodeParser $useNodeParser,
        FunctionNodeParser $functionNodeParser,
        ClassNodeParser $classNodeParser,
        TraitNodeParser $traitNodeParser,
        InterfaceNodeParser $interfaceNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\Namespace_::class] = $namespaceNodeParser;
        $this->nodeParsers[Node\Stmt\Use_::class]       = $useNodeParser;
        $this->nodeParsers[Node\Stmt\Function_::class]  = $functionNodeParser;
        $this->nodeParsers[Node\Stmt\Class_::class]     = $classNodeParser;
        $this->nodeParsers[Node\Stmt\Trait_::class]     = $traitNodeParser;
        $this->nodeParsers[Node\Stmt\Interface_::class] = $interfaceNodeParser;
    }
}
