<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Interface PhpFileModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface PhpFileModelInterface extends ClassLikeInterface, NodeInterface
{
    /**
     * Add a new PHP import.
     *
     * @param string $name     The name of this import (last component of name, or alias).
     * @param string $fullName The full name of this import.
     */
    public function addUse(string $name, string $fullName): void;

    /**
     * Check if the file contains a specific import.
     *
     * @param string $name The import to check.
     *
     * @return bool True if the file contains this import.
     */
    public function hasUse(string $name): bool;

    /**
     * Get the full name for a specific import.
     *
     * @param string $name The name of this import (last component of name, or alias).
     *
     * @return string|null The full name if the import exists, else null.
     */
    public function getFullNameUse(string $name): ?string;

    /**
     * @return string[] Imports contained in the file.
     */
    public function getUses(): array;

    /**
     * @param ClassModelInterface $class The class to add.
     */
    public function addClass(ClassModelInterface $class): void;

    /**
     * @return ClassModelInterface[] All classes contained in the file.
     */
    public function getClasses(): array;

    /**
     * @param TraitModelInterface $trait The trait to add.
     */
    public function addTrait(TraitModelInterface $trait): void;

    /**
     * @return TraitModelInterface[] All traits contained in the file.
     */
    public function getTraits(): array;

    /**
     * @param InterfaceModelInterface $interface The interface to add.
     */
    public function addInterface(InterfaceModelInterface $interface): void;

    /**
     * @return InterfaceModelInterface[] All interfaces contained in the file.
     */
    public function getInterfaces(): array;
}
