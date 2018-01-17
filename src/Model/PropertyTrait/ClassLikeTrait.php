<?php

namespace PhpUnitGen\Model\PropertyTrait;

use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Trait ClassLikeTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait ClassLikeTrait
{
    /**
     * @var FunctionModelInterface[] $functions Class functions.
     */
    private $functions = [];

    /**
     * @param FunctionModelInterface $function The function to add on this parent.
     */
    public function addFunction(FunctionModelInterface $function): void
    {
        $this->functions[] = $function;
    }

    /**
     * @return FunctionModelInterface[] All the functions contained in this parent.
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * @return int The number of function in this parent.
     */
    public function countFunctions(): int
    {
        return count($this->functions);
    }
}
