<?php

namespace Test\PHPUnitGenerator\Model;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Model\AbstractAnnotationModel;
use PHPUnitGenerator\Model\AnnotationSetModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Class AnnotationSetModelTest
 *
 * @covers \PHPUnitGenerator\Model\AnnotationSetModel
 */
class AnnotationSetModelTest extends TestCase
{
    /**
     * @var AnnotationSetModel $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of AnnotationSetModel
     */
    protected function setUp()
    {
        $this->instance = new AnnotationSetModel($this->createMock(MethodModelInterface::class));
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationSetModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(MethodModelInterface::class, $this->instance->getParentMethod());
        $this->assertEquals(AnnotationModelInterface::TYPE_GET, $this->instance->getType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationSetModel::getCall()
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

        $this->assertEquals('$method->invoke($this->instance, ', $this->instance->getCall());
        $this->assertEquals('$this->instance->myMethod(', $this->instance->getCall());
        $this->assertEquals('MyClass::myMethod(', $this->instance->getCall());
    }
}
