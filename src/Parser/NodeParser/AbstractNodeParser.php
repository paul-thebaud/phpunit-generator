<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\NodeParser;

use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\NodeParserInterface;
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
     * @var NodeParserInterface[] $nodeParsers Mapping array between PhpParser node class and PhpUnitGen node parser.
     */
    protected $nodeParsers = [];

    /**
     * {@inheritdoc}
     */
    public function parseSubNodes(array $nodes, NodeInterface $parent): void
    {
        foreach ($nodes as $node) {
            // Get the node class
            $class = get_class($node);

            // If a node parser exists
            if ($this->hasNodeParser($class)) {
                // Parse the node
                $this->getNodeParser($class)->invoke($node, $parent);
            }
        }
    }

    /**
     * Check if this node parser instance has a node parser.
     *
     * @param string $class The node parser for this class.
     *
     * @return bool True if the node parser exists.
     */
    protected function hasNodeParser(string $class): bool
    {
        if (Validator::key($class, Validator::instance(NodeParserInterface::class))
            ->validate($this->nodeParsers)) {
            return true;
        }
        return false;
    }

    /**
     * Get a node parser.
     *
     * @param string $class The node parser for this class.
     *
     * @return NodeParserInterface The node parser.
     *
     * @throws ParseException If the node parser does not exists.
     */
    protected function getNodeParser(string $class): NodeParserInterface
    {
        if ($this->hasNodeParser($class)) {
            return $this->nodeParsers[$class];
        }
        throw new ParseException(sprintf('The node parser for "%s" cannot be found', $class));
    }
}
