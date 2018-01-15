<?php

namespace PhpUnitGen\Parser\NodeParser\NodeParserInterface;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\VariableLikeInterface;

/**
 * Interface ValueNodeParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ValueNodeParserInterface
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Expr      $node   The node to parse.
     * @param VariableLikeInterface $parent The parent node.
     *
     * @return VariableLikeInterface The updated parent.
     */
    public function invoke(Node\Expr $node, VariableLikeInterface $parent): VariableLikeInterface;
}