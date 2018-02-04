<?php

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyInterface\DocumentationInterface;
use PhpUnitGen\Model\PropertyInterface\NameInterface;

/**
 * Interface InterfaceModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface InterfaceModelInterface extends NameInterface, ClassLikeInterface, DocumentationInterface
{
}
