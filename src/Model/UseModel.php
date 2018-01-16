<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\UseModelInterface;
use PhpUnitGen\Model\PropertyTrait\NameTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;

/**
 * Class UseModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class UseModel implements UseModelInterface
{
    use NameTrait;
    use NodeTrait;

    /**
     * @var string $fullName The full name of this use statement.
     */
    private $fullName;

    /**
     * @var string|null $alias The alias of this use statement.
     */
    private $alias;

    /**
     * {@inheritdoc}
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }
}