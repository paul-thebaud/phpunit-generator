<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyTrait\NamespaceTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;

/**
 * Class PhpFileModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpFileModel implements PhpFileModelInterface
{
    use NamespaceTrait;
    use NodeTrait;

    /**
     * This array is constructed with the name or the alias as key, and the real namespace, full name as a value.
     * @var string[] $uses Imports contained in the file.
     */
    private $uses = [];

    /**
     * @var FunctionModel[] $functions Functions contained in the file.
     */
    private $functions = [];

    /**
     * @var ClassModel[] $classes Classes contained in the file.
     */
    private $classes = [];

    /**
     * @var TraitModel[] $traits Traits contained in the file.
     */
    private $traits = [];

    /**
     * @var InterfaceModel[] $interfaces Interfaces contained in the file.
     */
    private $interfaces = [];

    /**
     * {@inheritdoc}
     */
    public function addUse(string $name, string $fullName): void
    {
        $this->uses[$name] = $fullName;
    }

    /**
     * {@inheritdoc}
     */
    public function hasUse(string $name): bool
    {
        return isset($this->uses[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFullNameUse(string $name): ?string
    {
        if ($this->hasUse($name)) {
            return $this->uses[$name];
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUses(): array
    {
        return $this->uses;
    }

    /**
     * {@inheritdoc}
     */
    public function addFunction(FunctionModel $function): void
    {
        $this->functions[] = $function;
        $function->setParentNode($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * {@inheritdoc}
     */
    public function addClass(ClassModel $class): void
    {
        $this->classes[] = $class;
        $class->setParentNode($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * {@inheritdoc}
     */
    public function addTrait(TraitModel $trait): void
    {
        $this->traits[] = $trait;
        $trait->setParentNode($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * {@inheritdoc}
     */
    public function addInterface(InterfaceModel $interface): void
    {
        $this->interfaces[] = $interface;
        $interface->setParentNode($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }
}