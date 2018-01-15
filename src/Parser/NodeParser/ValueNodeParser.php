<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\VariableLikeInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ValueNodeParserInterface;

/**
 * Class ValueNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ValueNodeParser extends AbstractNodeParser implements ValueNodeParserInterface
{
    /**
     * {@inheritdoc}
     *
     * This method do nothing because parsing expr is hard and useless for PhpUnitGen.
     */
    public function invoke(Node\Expr $node, VariableLikeInterface $parent): VariableLikeInterface
    {
        return $parent;
    }
}
