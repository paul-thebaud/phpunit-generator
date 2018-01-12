<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\InterfaceModel;
use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Model\PropertyInterface\NamespaceInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\TraitModel;

/**
 * Interface PhpFileModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface PhpFileModelInterface extends NameInterface, NamespaceInterface, NodeInterface
{
    /**
     * @param string|null $path The new path to this file.
     */
    public function setPath(?string $path): void;

    /**
     * @return string|null The path to the file, from the source directory.
     */
    public function getPath(): ?string;

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
     * @param FunctionModel $function The function to add.
     */
    public function addFunction(FunctionModel $function): void;

    /**
     * @return FunctionModel[] All functions contained in the file.
     */
    public function getFunctions(): array;

    /**
     * @param ClassModel $class The class to add.
     */
    public function addClass(ClassModel $class): void;

    /**
     * @return ClassModel[] All classes contained in the file.
     */
    public function getClasses(): array;

    /**
     * @param TraitModel $trait The trait to add.
     */
    public function addTrait(TraitModel $trait): void;

    /**
     * @return TraitModel[] All traits contained in the file.
     */
    public function getTraits(): array;

    /**
     * @param InterfaceModel $interface The interface to add.
     */
    public function addInterface(InterfaceModel $interface): void;

    /**
     * @return InterfaceModel[] All interfaces contained in the file.
     */
    public function getInterfaces(): array;
}
