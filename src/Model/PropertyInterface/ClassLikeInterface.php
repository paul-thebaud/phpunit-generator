<?php

namespace PhpUnitGen\Model\PropertyInterface;

use Doctrine\Common\Collections\Collection;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Interface ClassLikeInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ClassLikeInterface extends NodeInterface
{
    /**
     * @param FunctionModelInterface $function The function to add on this parent.
     */
    public function addFunction(FunctionModelInterface $function): void;

    /**
     * @return FunctionModelInterface[]|Collection All the functions contained in this parent.
     */
    public function getFunctions(): Collection;

    /**
     * @return int The number of testable (not abstract) function in this parent.
     */
    public function countNotAbstractFunctions(): int;
}
