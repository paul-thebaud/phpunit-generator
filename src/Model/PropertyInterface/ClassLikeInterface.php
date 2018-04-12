<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\PropertyInterface;

use Doctrine\Common\Collections\Collection;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Interface ClassLikeInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ClassLikeInterface extends NodeInterface, DocumentationInterface
{
    /**
     * @param FunctionModelInterface $function The function to add on this parent.
     */
    public function addFunction(FunctionModelInterface $function): void;

    /**
     * @return FunctionModelInterface[]|Collection All the functions contained in this parent.
     */
    public function getFunctions(): Collection;

    /**
     * Check if a function exists.
     *
     * @param string $name The function name.
     *
     * @return bool True if it exists.
     */
    public function hasFunction(string $name): bool;

    /**
     * Get a function if the function exists.
     *
     * @param string $name The name of the function.
     *
     * @return FunctionModelInterface|null The retrieved function, null if it does not exist.
     */
    public function getFunction(string $name): ?FunctionModelInterface;

    /**
     * @return int The number of testable (not abstract) function in this parent.
     */
    public function countNotAbstractFunctions(): int;
}
