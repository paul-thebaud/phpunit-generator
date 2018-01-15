<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\InterfaceModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class InterfaceNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class InterfaceNodeParser extends AbstractNodeParser
{
    /**
     * InterfaceNodeParser constructor.
     *
     * @param FunctionNodeParser $functionNodeParser The function node parser.
     */
    public function __construct(FunctionNodeParser $functionNodeParser)
    {
        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $functionNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\Interface_  $node   The interface node to parse.
         * @var PhpFileModelInterface $parent The node which contains this namespace.
         */
        $interface = new InterfaceModel();
        $interface->setName($node->name);

        $interface = $this->parseSubNodes($node->stmts, $interface);

        $parent->addInterface($interface);

        return $parent;
    }
}
