<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserTrait\DocumentationTrait;
use PhpUnitGen\Parser\NodeParserTrait\ParamTrait;
use PhpUnitGen\Parser\NodeParserTrait\VisibilityTrait;

/**
 * Class FunctionNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FunctionNodeParser extends AbstractNodeParser
{
    use VisibilityTrait;
    use DocumentationTrait;
    use ParamTrait;

    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\ClassMethod $node   The function node to parse.
         * @var ClassLikeInterface    $parent The node which contains this namespace.
         */
        $function = new FunctionModel();
        $function->setName($node->name);
        $function->setDocumentation($this->parseDocumentation($node));
        $function->setIsFinal($node->isFinal());
        $function->setIsStatic($node->isStatic());
        $function->setIsAbstract($node->isAbstract());
        $function->setVisibility($this->parseVisibility($node));

        foreach ($node->getParams() as $param) {
            $function->addParameter($this->parseParam($param));
        }

        $parent->addFunction($function);

        return $parent;
    }
}
