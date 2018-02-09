<?php

namespace PhpUnitGen\Parser\NodeParser\NodeParserInterface;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Interface NodeParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * A node parser interface must have the following method:
 * invoke(
 *      \PhpParser\Node $node,
 *      \PhpUnitGen\Model\PropertyInterface\NodeInterface $parent
 * ): PhpUnitGen\Model\PropertyInterface\NodeInterface;
 */
interface NodeParserInterface
{
    /**
     * Parse a node to update the parent node.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void;

    /**
     * Parse the node sub nodes to update parent.
     *
     * @param Node[]        $nodes  The nodes to parse.
     * @param NodeInterface $parent The parent.
     */
    public function parseSubNodes(array $nodes, NodeInterface $parent): void;
}
