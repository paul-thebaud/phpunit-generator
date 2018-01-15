<?php

namespace PhpUnitGen\Parser\NodeParserTrait;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\ParameterModelInterface;
use PhpUnitGen\Model\ParameterModel;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Trait ParamTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait ParamTrait
{
    /**
     * Retrieve the parameter model of a param node.
     *
     * @param Node\Param $node The param node.
     *
     * @return ParameterModelInterface The parameter model.
     */
    protected function parseParam(Node\Param $node): ParameterModelInterface
    {
        /** @todo Add a typeTrait and a valueTrait */
        /** @todo Move in a NodeParserInterface */
        $parameter = new ParameterModel();
        $parameter->setName($node->name);
        $parameter->setIsVariadic($node->variadic);
    }
}
