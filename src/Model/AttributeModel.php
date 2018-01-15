<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\AttributeModelInterface;
use PhpUnitGen\Model\PropertyTrait\NameTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;
use PhpUnitGen\Model\PropertyTrait\StaticTrait;
use PhpUnitGen\Model\PropertyTrait\ValueTrait;
use PhpUnitGen\Model\PropertyTrait\VisibilityTrait;

/**
 * Class AttributeModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AttributeModel implements AttributeModelInterface
{
    use NameTrait;
    use ValueTrait;
    use VisibilityTrait;
    use StaticTrait;
    use NodeTrait;
}
