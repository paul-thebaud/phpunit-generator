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
        TraitNodeParser $traitNodeParser
    ) {
        $this->nodeParsers[Node\Stmt\Namespace_::class] = $namespaceNodeParser;
        $this->nodeParsers[Node\Stmt\Use_::class]       = $useNodeParser;
        $this->nodeParsers[Node\Stmt\Trait_::class]     = $traitNodeParser;
        /** @todo */
    }
}
