<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\PropertyTrait;

use PhpUnitGen\Model\PropertyInterface\TypeInterface;

/**
 * Trait TypeTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait TypeTrait
{
    /**
     * @var int|null $type The type as an integer constant.
     */
    protected $type = TypeInterface::MIXED;

    /**
     * @var bool $nullable A boolean describing if it is nullable.
     */
    protected $nullable = false;

    /**
     * @var string|null $customType A custom type as a string (not null if the type is CUSTOM).
     */
    protected $customType = null;

    /**
     * @param int|null $type The new type to set.
     */
    public function setType(?int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int|null The current type.
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param bool $nullable The new nullable value to set.
     */
    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    /**
     * @return bool True if it is nullable.
     */
    public function nullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param string|null $customType The new custom type to set as a string.
     */
    public function setCustomType(?string $customType): void
    {
        $this->customType = $customType;
    }

    /**
     * @return string|null The current custom type as a string.
     */
    public function getCustomType(): ?string
    {
        return $this->customType;
    }
}
