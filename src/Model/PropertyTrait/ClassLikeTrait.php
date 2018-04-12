<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\PropertyTrait;

use Doctrine\Common\Collections\Collection;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Trait ClassLikeTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait ClassLikeTrait
{
    /**
     * @var FunctionModelInterface[]|Collection $functions Class functions.
     */
    protected $functions;

    /**
     * @param FunctionModelInterface $function The function to add on this parent.
     */
    public function addFunction(FunctionModelInterface $function): void
    {
        $this->functions->add($function);
    }

    /**
     * @return FunctionModelInterface[]|Collection All the functions contained in this parent.
     */
    public function getFunctions(): Collection
    {
        return $this->functions;
    }

    /**
     * Check if a function exists.
     *
     * @param string $name The function name.
     *
     * @return bool True if it exists.
     */
    public function hasFunction(string $name): bool
    {
        return $this->functions->exists(function (int $key, FunctionModelInterface $function) use ($name) {
            return $function->getName() === $name;
        });
    }

    /**
     * Get a function if the function exists.
     *
     * @param string $name The name of the function.
     *
     * @return FunctionModelInterface|null The retrieved function, null if it does not exist.
     */
    public function getFunction(string $name): ?FunctionModelInterface
    {
        $functions = $this->functions->filter(function (FunctionModelInterface $function) use ($name) {
            return $function->getName() === $name;
        });
        if ($functions->isEmpty()) {
            return null;
        }
        return $functions->first();
    }

    /**
     * @return int The number of testable (not abstract) function in this parent.
     */
    public function countNotAbstractFunctions(): int
    {
        return $this->functions->filter(function (FunctionModelInterface $function) {
            return ! $function->isAbstract();
        })->count();
    }
}
