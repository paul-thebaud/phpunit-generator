<?php

namespace PhpUnitGen\Parser\NodeParser;

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
 */
interface NodeParserInterface
{
    /**
     * Parse a node to update the parent node.
     *
     * @param Node          $node   The php node to parse.
     * @param NodeInterface $parent The parent node (for a method it is a class, etc).
     *
     * @return NodeInterface The updated node.
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface;

    /**
     * Parse the node sub nodes to update parent.
     *
     * @param Node[]        $nodes  The nodes to parse.
     * @param NodeInterface $parent The parent.
     *
     * @return NodeInterface The updated parent.
     */
    public function parseSubNodes(array $nodes, NodeInterface $parent): NodeInterface;
}
