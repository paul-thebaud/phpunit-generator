<?php

namespace PhpUnitGen\Parser\NodeParser\NodeParserInterface;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Interface ParameterNodeParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ParameterNodeParserInterface
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Param             $node   The node to parse.
     * @param FunctionModelInterface $parent The parent node.
     *
     * @return FunctionModelInterface The updated parent.
     */
    public function invoke(Node\Param $node, FunctionModelInterface $parent): FunctionModelInterface;
}