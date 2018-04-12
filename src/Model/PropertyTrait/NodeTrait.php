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

use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Trait NodeTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait NodeTrait
{
    /**
     * @var NodeInterface|null $parentNode The node parent.
     */
    protected $parentNode;

    /**
     * @param NodeInterface|null $parentNode The new parent node to set.
     */
    public function setParentNode(?NodeInterface $parentNode): void
    {
        $this->parentNode = $parentNode;
    }

    /**
     * @return NodeInterface|null The parent node if it exists.
     */
    public function getParentNode(): ?NodeInterface
    {
        return $this->parentNode;
    }
}
