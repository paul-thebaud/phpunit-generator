<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\ParameterModelInterface;
use PhpUnitGen\Model\ModelInterface\ReturnModelInterface;
use PhpUnitGen\Model\PropertyTrait\AbstractTrait;
use PhpUnitGen\Model\PropertyTrait\DocumentationTrait;
use PhpUnitGen\Model\PropertyTrait\FinalTrait;
use PhpUnitGen\Model\PropertyTrait\NameTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;
use PhpUnitGen\Model\PropertyTrait\StaticTrait;
use PhpUnitGen\Model\PropertyTrait\VisibilityTrait;

/**
 * Class FunctionModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FunctionModel implements FunctionModelInterface
{
    use NameTrait;
    use VisibilityTrait;
    use StaticTrait;
    use FinalTrait;
    use AbstractTrait;
    use NodeTrait;
    use DocumentationTrait;

    /**
     * @var ParameterModelInterface[] $parameters The function methods.
     */
    private $parameters = [];

    /**
     * @var ReturnModelInterface $return The function return.
     */
    private $return;

    /**
     * {@inheritdoc}
     */
    public function addParameter(ParameterModelInterface $parameter): void
    {
        $this->parameters[] = $parameter;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setReturn(ReturnModelInterface $return): void
    {
        $this->setReturn($return);
    }

    /**
     * {@inheritdoc}
     */
    public function getReturn(): ReturnModelInterface
    {
        return $this->return;
    }
}
