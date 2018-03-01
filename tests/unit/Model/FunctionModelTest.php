<?php

namespace UnitTests\PhpUnitGen\Model;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AssertAnnotation;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Annotation\MockAnnotation;
use PhpUnitGen\Annotation\ParamsAnnotation;
use PhpUnitGen\Annotation\SetAnnotation;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ParameterModel;
use PhpUnitGen\Model\ReturnModel;

/**
 * Class FunctionModelTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\FunctionModel
 */
class FunctionModelTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\FunctionModel::addParameter()
     * @covers \PhpUnitGen\Model\FunctionModel::getParameters()
     * @covers \PhpUnitGen\Model\FunctionModel::setReturn()
     * @covers \PhpUnitGen\Model\FunctionModel::getReturn()
     * @covers \PhpUnitGen\Model\FunctionModel::setIsGlobal()
     * @covers \PhpUnitGen\Model\FunctionModel::isGlobal()
     * @covers \PhpUnitGen\Model\FunctionModel::getParamsAnnotation()
     * @covers \PhpUnitGen\Model\FunctionModel::getGetAnnotation()
     * @covers \PhpUnitGen\Model\FunctionModel::getAssertAnnotations()
     * @covers \PhpUnitGen\Model\FunctionModel::getMockAnnotations()
     */
    public function testMethods(): void
    {
        $function = new FunctionModel();

        $parameter1 = new ParameterModel();
        $parameter2 = new ParameterModel();
        $this->assertSame(0, $function->getParameters()->count());
        $function->addParameter($parameter1);
        $function->addParameter($parameter2);
        $this->assertSame(2, $function->getParameters()->count());
        $this->assertSame($parameter1, $function->getParameters()->first());
        $this->assertSame($parameter2, $function->getParameters()->get(1));

        $return = new ReturnModel();
        $function->setReturn($return);
        $this->assertSame($return, $function->getReturn());

        $function->setIsGlobal(true);
        $this->assertTrue($function->isGlobal());

        $this->assertNull($function->getParamsAnnotation());
        $this->assertNull($function->getGetAnnotation());
        $this->assertNull($function->getSetAnnotation());
        $this->assertSame(0, $function->getAssertAnnotations()->count());
        $this->assertSame(0, $function->getMockAnnotations()->count());

        $paramAnnotation1 = new ParamsAnnotation();
        $paramAnnotation2 = new ParamsAnnotation();
        $function->addAnnotation($paramAnnotation1);
        $function->addAnnotation($paramAnnotation2);
        $this->assertSame($paramAnnotation1, $function->getParamsAnnotation());

        $getAnnotation1 = new GetAnnotation();
        $getAnnotation2 = new GetAnnotation();
        $function->addAnnotation($getAnnotation1);
        $function->addAnnotation($getAnnotation2);
        $this->assertSame($getAnnotation1, $function->getGetAnnotation());

        $setAnnotation1 = new SetAnnotation();
        $setAnnotation2 = new SetAnnotation();
        $function->addAnnotation($setAnnotation1);
        $function->addAnnotation($setAnnotation2);
        $this->assertSame($setAnnotation1, $function->getSetAnnotation());

        $assertAnnotation1 = new AssertAnnotation();
        $assertAnnotation2 = new AssertAnnotation();
        $function->addAnnotation($assertAnnotation1);
        $function->addAnnotation($assertAnnotation2);
        $this->assertSame(2, $function->getAssertAnnotations()->count());
        $this->assertSame($assertAnnotation1, $function->getAssertAnnotations()->first());
        $this->assertSame($assertAnnotation2, $function->getAssertAnnotations()->next());

        $mockAnnotation1 = new MockAnnotation();
        $mockAnnotation2 = new MockAnnotation();
        $function->addAnnotation($mockAnnotation1);
        $function->addAnnotation($mockAnnotation2);
        $this->assertSame(2, $function->getMockAnnotations()->count());
        $this->assertSame($mockAnnotation1, $function->getMockAnnotations()->first());
        $this->assertSame($mockAnnotation2, $function->getMockAnnotations()->next());
    }
}
