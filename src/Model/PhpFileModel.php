<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Model\ModelInterface\ClassModelInterface;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
use PhpUnitGen\Model\PropertyTrait\ClassLikeTrait;
use PhpUnitGen\Model\PropertyTrait\NamespaceTrait;
use PhpUnitGen\Model\PropertyTrait\NameTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;
use Respect\Validation\Validator;

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
    use NameTrait;
    use NamespaceTrait;
    use NodeTrait;
    use ClassLikeTrait;

    /**
     * This array is constructed with the full name as key, and the class name as a value.
     * @var string[] $uses Imports needed for tests skeletons.
     */
    private $concreteUses = [];

    /**
     * @var string[] $uses Imports contained in the file.
     */
    private $uses = [];

    /**
     * @var ClassModelInterface[] $classes Classes contained in the file.
     */
    private $classes = [];

    /**
     * @var TraitModelInterface[] $traits Traits contained in the file.
     */
    private $traits = [];

    /**
     * @var InterfaceModelInterface[] $interfaces Interfaces contained in the file.
     */
    private $interfaces = [];

    /**
     * {@inheritdoc}
     */
    public function getTestableFunctionsCount(): int
    {
        $sum = $this->countFunctions();
        foreach ($this->getTraits() as $trait) {
            $sum += $trait->countFunctions();
        }
        foreach ($this->getClasses() as $class) {
            $sum += $class->countFunctions();
        }
        return $sum;
    }

    /**
     * {@inheritdoc}
     */
    public function getInterfacesFunctionsCount(): int
    {
        $sum = 0;
        foreach ($this->getInterfaces() as $interface) {
            $sum += $interface->countFunctions();
        }
        return $sum;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullNameFor(string $name): string
    {
        $namespace = $this->getNamespaceString();
        return $namespace === null? $name : $namespace . '\\' . $name;
    }

    /**
     * {@inheritdoc}
     */
    public function addConcreteUse(string $fullName, string $name): void
    {
        // Full name exists and concrete use correspond to this one
        if (Validator::key($fullName)->validate($this->concreteUses)
            && $name === $this->concreteUses[$fullName]
        ) {
            // Do not add
            return;
        }

        // Delete duplicate class name
        $iteration = 0;
        while (Validator::contains($name)->validate($this->concreteUses)) {
            if ($iteration === 0 && Validator::contains($fullName)->validate($this->uses)) {
                // If a known alias exists
                $name = array_search($fullName, $this->uses);
            } else {
                // Give a default alias
                $name .= 'Alias';
            }
            $iteration++;
        }
        $this->concreteUses[$fullName] = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getConcreteUses(): array
    {
        return $this->concreteUses;
    }

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
        return Validator::key($name)->validate($this->uses);
    }

    /**
     * {@inheritdoc}
     */
    public function getUse(string $name): string
    {
        if (! $this->hasUse($name)) {
            throw new ParseException(sprintf(
                'Trying to get a full class name for "%s", but it does not exists',
                $name
            ));
        }
        return $this->uses[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function addClass(ClassModelInterface $class): void
    {
        $this->classes[] = $class;
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
    public function addTrait(TraitModelInterface $trait): void
    {
        $this->traits[] = $trait;
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
    public function addInterface(InterfaceModelInterface $interface): void
    {
        $this->interfaces[] = $interface;
    }

    /**
     * {@inheritdoc}
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }
}
