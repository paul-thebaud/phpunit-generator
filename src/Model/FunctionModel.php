<?php

namespace PhpUnitGen\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var ParameterModelInterface[]|Collection $parameters The function methods.
     */
    private $parameters;

    /**
     * @var ReturnModelInterface $return The function return.
     */
    private $return;

    /**
     * @var bool $isGlobal Tells if the function is global.
     */
    private $isGlobal = false;

    /**
     * FunctionModel constructor.
     */
    public function __construct()
    {
        $this->parameters  = new ArrayCollection();
        $this->annotations = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addParameter(ParameterModelInterface $parameter): void
    {
        $this->parameters->add($parameter);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setReturn(ReturnModelInterface $return): void
    {
        $this->return = $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getReturn(): ReturnModelInterface
    {
        return $this->return;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsGlobal(bool $isGlobal): void
    {
        $this->isGlobal = true;
    }

    /**
     * {@inheritdoc}
     */
    public function isGlobal(): bool
    {
        return $this->isGlobal;
    }
}
