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
 * Interface NodeInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface NodeInterface
{
    /**
     * @param NodeInterface|null $parentNode The new parent node to set.
     */
    public function setParentNode(?NodeInterface $parentNode): void;

    /**
     * @return NodeInterface|null The parent node if it exists.
     */
    public function getParentNode(): ?NodeInterface;
}
