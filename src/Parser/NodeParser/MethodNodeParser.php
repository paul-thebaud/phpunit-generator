<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class MethodNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class MethodNodeParser extends AbstractNodeParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\ClassMethod $node   The method node to parse.
         * @var ClassLikeInterface    $parent The node which contains this namespace.
         */
        $function = new FunctionModel();
        $function->setName($node->name);

        $parent->addFunction($function);

        return $parent;
    }
}
