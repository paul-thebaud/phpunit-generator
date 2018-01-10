<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\AbstractInterface;
use PhpUnitGen\Model\PropertyInterface\FinalInterface;
use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Model\PropertyInterface\NamespaceInterface;
use PhpUnitGen\Model\PropertyInterface\ParentInterface;
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
interface FunctionModelInterface extends NamespaceInterface, NameInterface, VisibilityInterface, StaticInterface,
    FinalInterface, AbstractInterface
{
    /**
     * @param ParentInterface|null $parent The new parent to use.
     */
    public function setParent(?ParentInterface $parent): void;

    /**
     * @return ParentInterface|null The parent of this function (a class, a trait or an interface), null there is
     *                                   no parent.
     */
    public function getParent(): ?ParentInterface;

    /**
     * @param ParameterModelInterface $parameter A new parameter for this function.
     */
    public function addParameter(ParameterModelInterface $parameter): void;

    /**
     * @return ParameterModelInterface[] All parameters of this function.
     */
    public function getParameters(): array;
}
