<?php

namespace PhpUnitGen\Parser\NodeParser\NodeParserInterface;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;

/**
 * Interface MethodNodeParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface MethodNodeParserInterface extends NodeParserInterface
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\ClassMethod   $node   The node to parse.
     * @param InterfaceModelInterface $parent The parent node.
     *
     * @return InterfaceModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\ClassMethod $node, InterfaceModelInterface $parent): InterfaceModelInterface;
}
