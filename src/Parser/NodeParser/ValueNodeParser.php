<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\PropertyInterface\VariableLikeInterface;

/**
 * Class ValueNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ValueNodeParser extends AbstractNodeParser
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     *
     * This method do nothing because parsing expr is hard and useless for PhpUnitGen.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Expr || ! $parent instanceof VariableLikeInterface) {
            throw new Exception('ValueNodeParser is made to parse a expression node');
        }
    }
}
