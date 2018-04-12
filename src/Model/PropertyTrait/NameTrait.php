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

use PhpUnitGen\Model\PropertyInterface\NameInterface;

/**
 * Trait NameTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait NameTrait
{
    /**
     * @var string $name A string describing a name.
     */
    protected $name = NameInterface::UNKNOWN_NAME;

    /**
     * @param string $name The new name to be set.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string The current name.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
