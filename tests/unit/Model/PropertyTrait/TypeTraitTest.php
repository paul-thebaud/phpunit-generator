<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ParameterModel;
use PhpUnitGen\Model\PropertyInterface\TypeInterface;

/**
 * Class TypeTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\TypeTrait
 */
class TypeTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\TypeTrait::setType()
     * @covers \PhpUnitGen\Model\PropertyTrait\TypeTrait::getType()
     */
    public function testMethods(): void
    {
        $parameter = new ParameterModel();
        $parameter->setType(TypeInterface::OBJECT);
        $this->assertSame(TypeInterface::OBJECT, $parameter->getType());
        $parameter->setNullable(true);
        $this->assertTrue($parameter->nullable());
        $this->assertNull($parameter->getCustomType());
        $parameter->setCustomType('MyClass');
        $this->assertSame('MyClass', $parameter->getCustomType());
    }
}
