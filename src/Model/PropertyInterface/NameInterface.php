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

/**
 * Interface NameInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface NameInterface
{
    /**
     * @var string UNKNOWN A string describing an unknown name.
     */
    public const UNKNOWN_NAME = 'UNKNOWN_NAME';

    /**
     * @param string $name The new name to be set.
     */
    public function setName(string $name): void;

    /**
     * @return string The current name.
     */
    public function getName(): string;
}
