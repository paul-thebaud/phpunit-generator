<?php

namespace PhpUnitGen\Model\ModelInterface;

use Doctrine\Common\Collections\Collection;
use PhpUnitGen\Annotation\AssertAnnotation;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Annotation\MockAnnotation;
use PhpUnitGen\Annotation\ParamsAnnotation;
use PhpUnitGen\Annotation\SetAnnotation;
use PhpUnitGen\Model\PropertyInterface\AbstractInterface;
use PhpUnitGen\Model\PropertyInterface\DocumentationInterface;
use PhpUnitGen\Model\PropertyInterface\FinalInterface;
use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\PropertyInterface\StaticInterface;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Interface FunctionModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface FunctionModelInterface extends
    NameInterface,
    VisibilityInterface,
    StaticInterface,
    FinalInterface,
    AbstractInterface,
    NodeInterface,
    DocumentationInterface
{
    /**
     * @param ParameterModelInterface $parameter A new parameter for this function.
     */
    public function addParameter(ParameterModelInterface $parameter): void;

    /**
     * @return ParameterModelInterface[]|Collection All parameters of this function.
     */
    public function getParameters(): Collection;

    /**
     * @param ReturnModelInterface $return The new return to be set.
     */
    public function setReturn(ReturnModelInterface $return): void;

    /**
     * @return ReturnModelInterface The current return.
     */
    public function getReturn(): ReturnModelInterface;

    /**
     * @param bool $isGlobal The new global value to set.
     */
    public function setIsGlobal(bool $isGlobal): void;

    /**
     * @return bool True if the function is global.
     */
    public function isGlobal(): bool;

    /**
     * @return ParamsAnnotation|null The params annotation, null if none.
     */
    public function getParamsAnnotation(): ?ParamsAnnotation;

    /**
     * @return GetAnnotation|null The getter annotation, null if none.
     */
    public function getGetAnnotation(): ?GetAnnotation;

    /**
     * @return SetAnnotation|null The set annotation, null if none.
     */
    public function getSetAnnotation(): ?SetAnnotation;

    /**
     * @return Collection|AssertAnnotation[] The assertion annotations.
     */
    public function getAssertAnnotations(): Collection;

    /**
     * @return Collection|MockAnnotation[] The mock annotations.
     */
    public function getMockAnnotations(): Collection;
}
