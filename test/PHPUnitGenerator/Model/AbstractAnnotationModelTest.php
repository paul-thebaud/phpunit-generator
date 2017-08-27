<?php

namespace Test\PHPUnitGenerator\Model;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Model\AbstractAnnotationModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Class AbstractAnnotationModelTest
 *
 * @covers \PHPUnitGenerator\Model\AbstractAnnotationModel
 */
class AbstractAnnotationModelTest extends TestCase
{
    /**
     * The abstract class instance to test
     * @var AbstractAnnotationModel $instance
     */
    protected $instance;

    /**
     * Build the instance of AbstractAnnotationModel
     */
    protected function setUp()
    {
        $this->instance = $this->getMockBuilder(AbstractAnnotationModel::class)
            ->setConstructorArgs([$this->createMock(MethodModelInterface::class)])
            ->getMockForAbstractClass();
    }

    /**
     * @covers \PHPUnitGenerator\Model\AbstractAnnotationModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(MethodModelInterface::class, $this->instance->getParentMethod());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AbstractAnnotationModel::getType()
     */
    public function testGetType()
    {
        $expected = AnnotationModelInterface::TYPE_GET;

        $property = (new \ReflectionClass(AbstractAnnotationModel::class))->getProperty('type');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AbstractAnnotationModel::setType()
     */
    public function testSetType()
    {
        $expected = AnnotationModelInterface::TYPE_GET;

        $property = (new \ReflectionClass(AbstractAnnotationModel::class))->getProperty('type');
        $property->setAccessible(true);

        $this->instance->setType($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\AbstractAnnotationModel::getParentMethod()
     */
    public function testGetParentMethod()
    {
        $expected = $this->createMock(MethodModelInterface::class);

        $property = (new \ReflectionClass(AbstractAnnotationModel::class))->getProperty('method');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getParentMethod());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AbstractAnnotationModel::getCall()
     */
    public function testGetCall()
    {
        $class = $this->createMock(ClassModelInterface::class);
        $class->method('getName')->willReturn('MyClass');

        $method = $this->createMock(MethodModelInterface::class);
        $method->method('isPublic')
            ->willReturnOnConsecutiveCalls(false, true, true);
        $method->method('isStatic')
            ->willReturnOnConsecutiveCalls(false, true);
        $method->method('getObjectToUse')->willReturn('$this->instance');
        $method->method('getName')->willReturn('myMethod');
        $method->method('getParentClass')->willReturn($class);

        $property = (new \ReflectionClass(AbstractAnnotationModel::class))->getProperty('method');
        $property->setAccessible(true);
        $property->setValue($this->instance, $method);

        $this->assertEquals('$method->invoke($this->instance', $this->instance->getCall());
        $this->assertEquals('$this->instance->myMethod(', $this->instance->getCall());
        $this->assertEquals('MyClass::myMethod(', $this->instance->getCall());
    }
}
