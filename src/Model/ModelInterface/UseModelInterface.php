<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Interface UseModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface UseModelInterface extends NameInterface, NodeInterface
{
    /**
     * @param string $fullName The new full name to set.
     */
    public function setFullName(string $fullName): void;

    /**
     * @return string The current full name.
     */
    public function getFullName(): string;

    /**
     * @param string|null $alias The new alias to set.
     */
    public function setAlias(?string $alias): void;

    /**
     * @return string|null The current alias, null if none.
     */
    public function getAlias(): ?string;
}
