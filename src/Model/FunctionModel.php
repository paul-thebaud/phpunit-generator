<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\ParameterModelInterface;
use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyTrait\AbstractTrait;
use PhpUnitGen\Model\PropertyTrait\FinalTrait;
use PhpUnitGen\Model\PropertyTrait\InPhpFileTrait;
use PhpUnitGen\Model\PropertyTrait\NamespaceTrait;
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
    use InPhpFileTrait;
    use NodeTrait;

    /**
     * @var ClassLikeInterface|null $parent The parent which contains this function.
     */
    private $parent;

    /**
     * @var ParameterModel[] $parameters The function methods.
     */
    private $parameters = [];

    /**
     * @var ReturnModel $return The function return.
     */
    private $return;

    /**
     * {@inheritdoc}
     */
    public function setParent(?ClassLikeInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?ClassLikeInterface
    {
        return $this->parent;
    }

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
}
