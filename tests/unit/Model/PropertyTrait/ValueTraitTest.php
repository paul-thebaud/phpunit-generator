<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\ParameterModel;

/**
 * Class ValueTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers \PhpUnitGen\Model\PropertyTrait\ValueTrait
 */
class ValueTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\ValueTrait::setValue()
     * @covers \PhpUnitGen\Model\PropertyTrait\ValueTrait::getValue()
     */
    public function testMethods(): void
    {
        $parameter = new ParameterModel();
        $parameter->setValue('"a string value"');
        $this->assertSame('"a string value"', $parameter->getValue());
    }
}
