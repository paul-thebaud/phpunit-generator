<?php

namespace PhpUnitGen\Model\PropertyTrait;

/**
 * Trait DocumentationTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait DocumentationTrait
{
    /**
     * @var string|null $documentation The documentation.
     */
    protected $documentation;

    /**
     * @param string|null $documentation The new documentation to be set.
     */
    public function setDocumentation(?string $documentation): void
    {
        $this->documentation = $documentation;
    }

    /**
     * @return string|null The current documentation.
     */
    public function getDocumentation(): ?string
    {
        return $this->documentation;
    }
}
