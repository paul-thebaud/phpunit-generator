<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\PropertyInterface\TypeInterface;
use PhpUnitGen\Model\PropertyInterface\ValueInterface;

/**
 * Interface ParameterModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ParameterModelInterface extends NameInterface, TypeInterface, ValueInterface, NodeInterface
{
    /**
     * @param bool $isVariadic The new variadic value to set.
     */
    public function setIsVariadic(bool $isVariadic): void;

    /**
     * @return bool True if it is variadic.
     */
    public function isVariadic(): bool;
}
