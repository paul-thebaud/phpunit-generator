<?php

namespace PhpUnitGen\Model\PropertyTrait;

use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Trait VisibilityTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait VisibilityTrait
{
    /**
     * @var int|null $visibility The visibility as an integer constant.
     */
    protected $visibility = VisibilityInterface::UNKNOWN;

    /**
     * @param int|null $visibility The new visibility to set.
     */
    public function setVisibility(?int $visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * @return int|null The current visibility.
     */
    public function getVisibility(): ?int
    {
        return $this->visibility;
    }
}
