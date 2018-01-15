<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use Respect\Validation\Validator;

/**
 * Class AbstractNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractNodeParser implements NodeParserInterface
{
    /**
     * @var NodeParserInterface[] $nodeParsers The array of node parsers. A key is node class, and a value is the
     *      parser for this class.
     */
    protected $nodeParsers = [];

    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        // By default, only return the parent node.
        return $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function parseSubNodes(array $nodes, NodeInterface $parent): NodeInterface
    {
        if (Validator::arrayType()->length(1, null)->validate($nodes)) {
            foreach ($nodes as $node) {
                $parent = $this->parseSubNode($node, $parent);
            }
        }
        return $parent;
    }

    /**
     * Parse a sub node to update parent.
     *
     * @param Node          $node   The node to parse.
     * @param NodeInterface $parent The parent.
     *
     * @return NodeInterface The updated parent.
     */
    protected function parseSubNode(Node $node, NodeInterface $parent): NodeInterface
    {
        $class = get_class($node);

        var_dump('I want to parse a "' . get_class($node) . '"');
        if (Validator::key($class, Validator::instance(NodeParserInterface::class))
            ->validate($this->nodeParsers)
        ) {
            var_dump('Im gonna parse a "' . get_class($node) . '"');
            $parent = $this->nodeParsers[$class]->parse($node, $parent);
        }

        return $parent;
    }
}
