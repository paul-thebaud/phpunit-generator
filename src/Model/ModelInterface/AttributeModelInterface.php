<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\PropertyInterface\StaticInterface;
use PhpUnitGen\Model\PropertyInterface\ValueInterface;
use PhpUnitGen\Model\PropertyInterface\VariableLikeInterface;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Interface AttributeModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface AttributeModelInterface extends VariableLikeInterface, VisibilityInterface, StaticInterface
{
}
