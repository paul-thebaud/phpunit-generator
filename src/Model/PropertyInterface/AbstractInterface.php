<?php

namespace PhpUnitGen\Model\PropertyInterface;

/**
 * Interface AbstractInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface AbstractInterface
{
    /**
     * @param bool $isAbstract The new abstract value to set.
     */
    public function setIsAbstract(bool $isAbstract): void;

    /**
     * @return bool True if it is abstract.
     */
    public function isAbstract(): bool;
}
