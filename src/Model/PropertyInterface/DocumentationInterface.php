<?php

namespace PhpUnitGen\Model\PropertyInterface;

/**
 * Interface DocumentationInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface DocumentationInterface
{
    /**
     * @param string|null $documentation The new documentation to be set.
     */
    public function setDocumentation(?string $documentation): void;

    /**
     * @return string|null The current documentation.
     */
    public function getDocumentation(): ?string;
}
