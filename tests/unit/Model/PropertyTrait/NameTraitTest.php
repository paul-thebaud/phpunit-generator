<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\ClassModel;

/**
 * Class NameTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\NameTrait
 */
class NameTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\NameTrait::setName()
     * @covers \PhpUnitGen\Model\PropertyTrait\NameTrait::getName()
     */
    public function testMethods(): void
    {
        $class = new ClassModel();
        $class->setName('MyClass');
        $this->assertSame('MyClass', $class->getName());
    }
}
