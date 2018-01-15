<?php

namespace PhpUnitGen\Generator\GeneratorInterface;

use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Interface GeneratorInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface GeneratorInterface
{
    public function invoke(NodeInterface $node): string;
}
