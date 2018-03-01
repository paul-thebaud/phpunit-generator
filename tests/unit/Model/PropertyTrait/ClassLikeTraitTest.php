<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\FunctionModel;

/**
 * Class ClassLikeTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\ClassLikeTrait
 */
class ClassLikeTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\ClassLikeTrait::addFunction()
     * @covers \PhpUnitGen\Model\PropertyTrait\ClassLikeTrait::getFunctions()
     * @covers \PhpUnitGen\Model\PropertyTrait\ClassLikeTrait::hasFunction()
     * @covers \PhpUnitGen\Model\PropertyTrait\ClassLikeTrait::getFunction()
     * @covers \PhpUnitGen\Model\PropertyTrait\ClassLikeTrait::countNotAbstractFunctions()
     */
    public function testMethods(): void
    {
        $class = new ClassModel();

        $function1 = new FunctionModel();
        $function1->setName('function1');
        $function1->setIsAbstract(true);
        $function2 = new FunctionModel();
        $function2->setName('function2');

        $this->assertSame(0, $class->countNotAbstractFunctions());
        $this->assertSame(0, $class->getFunctions()->count());
        $this->assertFalse($class->hasFunction('function1'));
        $this->assertNull($class->getFunction('function2'));

        $class->addFunction($function1);
        $class->addFunction($function2);

        $this->assertSame(1, $class->countNotAbstractFunctions());
        $this->assertSame(2, $class->getFunctions()->count());
        $this->assertSame($function1, $class->getFunctions()->first());
        $this->assertSame($function2, $class->getFunctions()->get(1));
        $this->assertTrue($class->hasFunction('function1'));
        $this->assertSame($function1, $class->getFunction('function1'));
        $this->assertTrue($class->hasFunction('function2'));
        $this->assertSame($function2, $class->getFunction('function2'));
    }
}
