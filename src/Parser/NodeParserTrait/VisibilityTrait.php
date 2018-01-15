<?php

namespace PhpUnitGen\Parser\NodeParserTrait;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Trait VisibilityTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait VisibilityTrait
{
    /**
     * Retrieve the visibility of a node.
     *
     * @param Node $node The node.
     *
     * @return int The visibility as an integer.
     */
    protected function parseVisibility(Node $node): int
    {
        if (! method_exists($node, 'isPrivate')) {
            return VisibilityInterface::UNKNOWN;
        }
        if ($node->isPrivate()) {
            return VisibilityInterface::PRIVATE;
        }
        if ($node->isProtected()) {
            return VisibilityInterface::PROTECTED;
        }
        return VisibilityInterface::PUBLIC;
    }
}
