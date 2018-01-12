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
     * Parse a node to create a child node or update the $parent node.
     *
     * @param Node          $nodeToParse The php node to parse.
     * @param NodeInterface $node        The node parent (for a method it is a class, etc).
     *
     * @return NodeInterface The created or updated node.
     */
    public function parse(Node $nodeToParse, NodeInterface $node): NodeInterface;
}
