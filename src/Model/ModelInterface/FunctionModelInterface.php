<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\AbstractInterface;
use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyInterface\FinalInterface;
use PhpUnitGen\Model\PropertyInterface\InPhpFileInterface;
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
    InPhpFileInterface,
    NodeInterface
{
    /**
     * @param ClassLikeInterface|null $parent The new parent to use.
     */
    public function setParent(?ClassLikeInterface $parent): void;

    /**
     * @return ClassLikeInterface|null The parent of this function (a class, a trait or an interface), null there is
     *                                   no parent.
     */
    public function getParent(): ?ClassLikeInterface;

    /**
     * @param ParameterModelInterface $parameter A new parameter for this function.
     */
    public function addParameter(ParameterModelInterface $parameter): void;

    /**
     * @return ParameterModelInterface[] All parameters of this function.
     */
    public function getParameters(): array;
}
