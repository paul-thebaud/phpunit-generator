<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Model\PropertyTrait\NamespaceTrait;
use PhpUnitGen\Model\PropertyTrait\NameTrait;

/**
 * Class InterfaceModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class InterfaceModel implements InterfaceModelInterface
{
    use NamespaceTrait;
    use NameTrait;

    /**
     * @var FunctionModel[] $functions Class functions.
     */
    private $functions = [];

    /**
     * {@inheritdoc}
     */
    public function addFunction(FunctionModelInterface $function): void
    {
        $this->functions[] = $function;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }
}
