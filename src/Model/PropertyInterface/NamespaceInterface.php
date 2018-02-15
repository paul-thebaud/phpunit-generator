<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\PropertyInterface;

/**
 * Interface NamespaceInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface NamespaceInterface
{
    /**
     * @param string[] $namespace The new namespace to be set.
     */
    public function setNamespace(array $namespace): void;

    /**
     * @return string[] The current namespace.
     */
    public function getNamespace(): array;

    /**
     * @return string|null The concat namespace parts.
     */
    public function getNamespaceString(): ?string;

    /**
     * @return string|null The last namespace part.
     */
    public function getNamespaceLast(): ?string;
}
