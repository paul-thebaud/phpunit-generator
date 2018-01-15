<?php

namespace PhpUnitGen\Generator;

use PhpUnitGen\Generator\GeneratorInterface\GeneratorInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class AbstractGenerator.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function invoke(NodeInterface $node): string;
}