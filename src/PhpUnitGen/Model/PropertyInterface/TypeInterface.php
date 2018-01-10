<?php

namespace PhpUnitGen\Model\PropertyInterface;

/**
 * Interface TypeInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface TypeInterface
{
    /**
     * @var null MIXED A mixed value (can be whatever).
     */
    const MIXED = null;

    /**
     * @var int CUSTOM A custom type hint (class, interface ...).
     */
    const CUSTOM = 0;

    /**
     * @var int OBJECT An object value (type hint "object").
     */
    const OBJECT = 1;

    /**
     * @var int BOOL A bool value (true or false).
     */
    const BOOL = 2;

    /**
     * @var int INT A int value (a PHP integer: ... -1, 0, 1 ...).
     */
    const INT = 3;

    /**
     * @var int FLOAT A float value (a PHP float: ... -0.1, 1.0, 1.01 ...).
     */
    const FLOAT = 4;

    /**
     * @var int ARRAY A array value (a PHP array: [1, 2, 3]).
     */
    const ARRAY = 5;

    /**
     * @var int ITERABLE A iterable value (a PHP iterable: array or Traversable).
     */
    const ITERABLE = 6;

    /**
     * @var int CALLABLE A callable value (a PHP callable: .
     */
    const CALLABLE = 7;

    /**
     * @param int|null $type The new type to set.
     */
    public function setType(?int $type): void;

    /**
     * @return int|null The current type.
     */
    public function getType(): ?int;

    /**
     * @param bool $nullable The new nullable value to set.
     */
    public function setNullable(bool $nullable): void;

    /**
     * @return bool True if it is nullable.
     */
    public function nullable(): bool;
}
