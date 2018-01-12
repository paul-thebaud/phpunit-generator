<?php

namespace PhpUnitGen\Model\PropertyTrait;

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
    protected $name = 'UNKNOWN';

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
