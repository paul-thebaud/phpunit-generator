<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\FunctionModel;

/**
 * Class StaticTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\StaticTrait
 */
class StaticTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\StaticTrait::setIsStatic()
     * @covers \PhpUnitGen\Model\PropertyTrait\StaticTrait::isStatic()
     */
    public function testMethods(): void
    {
        $class = new FunctionModel();
        $class->setIsStatic(true);
        $this->assertTrue($class->isStatic());
    }
}
