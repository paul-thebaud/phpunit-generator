<?php

namespace PhpUnitGen\Model\PropertyInterface;

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
interface ClassLikeInterface extends NamespaceInterface
{
    /**
     * @param FunctionModelInterface $function The function to add on this parent.
     */
    public function addFunction(FunctionModelInterface $function): void;

    /**
     * @return FunctionModelInterface[] All the functions contained in this parent.
     */
    public function getFunctions(): array;
}
