<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\ModelInterface;

use Doctrine\Common\Collections\Collection;

/**
 * Interface TraitModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface TraitModelInterface extends InterfaceModelInterface
{
    /**
     * @param AttributeModelInterface $attribute The new attribute to add.
     */
    public function addAttribute(AttributeModelInterface $attribute): void;

    /**
     * @return AttributeModelInterface[]|Collection All attributes contained.
     */
    public function getAttributes(): Collection;

    /**
     * Check if an attribute exists.
     *
     * @param string $name   The name of the attribute.
     * @param bool   $static If the attribute needs to be static or not.
     *
     * @return bool True if it exists.
     */
    public function hasAttribute(string $name, bool $static = false): bool;

    /**
     * Get an attribute if exists.
     *
     * @param string $name The name of the attribute.
     *
     * @return AttributeModelInterface|null The attribute if exists, else null.
     */
    public function getAttribute(string $name): ?AttributeModelInterface;
}
