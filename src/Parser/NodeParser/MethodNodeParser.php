<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Parser\NodeParserUtil\MethodVisibilityHelper;

/**
 * Class MethodNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class MethodNodeParser extends AbstractFunctionNodeParser
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\ClassMethod   $node   The node to parse.
     * @param InterfaceModelInterface $parent The parent node.
     *
     * @return InterfaceModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\ClassMethod $node, InterfaceModelInterface $parent): InterfaceModelInterface
    {
        $function = new FunctionModel();
        $function->setParentNode($parent);
        $function->setName($node->name);
        $function->setIsFinal($node->isFinal());
        $function->setIsStatic($node->isStatic());
        $function->setIsAbstract($node->isAbstract());
        $function->setVisibility(MethodVisibilityHelper::getMethodVisibility($node));

        $function = $this->parseFunction($node, $function);

        $parent->addFunction($function);

        return $parent;
    }
}
