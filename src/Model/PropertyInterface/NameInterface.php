<?php

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
    const UNKNOWN_NAME = 'UNKNOWN';

    /**
     * @param string $name The new name to be set.
     */
    public function setName(string $name): void;

    /**
     * @return string The current name.
     */
    public function getName(): string;
}
