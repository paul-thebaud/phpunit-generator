<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\PropertyTrait;

/**
 * Trait AbstractTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait AbstractTrait
{
    /**
     * @var bool $isAbstract A boolean describing if it is abstract.
     */
    protected $isAbstract = false;

    /**
     * @param bool $isAbstract The new abstract value to set.
     */
    public function setIsAbstract(bool $isAbstract): void
    {
        $this->isAbstract = $isAbstract;
    }

    /**
     * @return bool True if it is abstract.
     */
    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }
}
