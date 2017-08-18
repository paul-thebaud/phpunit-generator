<?php

namespace Test\PHPUnitGenerator\Model\AnnotationBaseModel;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Model\AnnotationBaseModel;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Class AnnotationBaseModelTest
 *
 * @covers \PHPUnitGenerator\Model\AnnotationBaseModel
 */
class AnnotationBaseModelTest extends TestCase
{
    /**
     * @var AnnotationBaseModel $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of AnnotationBaseModel
     */
    protected function setUp()
    {
        $this->instance = new AnnotationBaseModel(
            'AssertSomething',
            '()',
            $this->createMock(MethodModelInterface::class)
        );
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationBaseModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(MethodModelInterface::class, $this->instance->getParentMethod());
        $this->assertInternalType('string', $this->instance->getMethodName());
        $this->assertEmpty($this->instance->getExpected());
        $this->assertEmpty($this->instance->getArguments());

        $this->instance = new AnnotationBaseModel(
            'AssertSomething',
            '("An expected value":{"arg1", "arg2"})',
            $this->createMock(MethodModelInterface::class)
        );

        $this->assertEquals('assertSomething', $this->instance->getMethodName());
        $this->assertEquals('"An expected value", ', $this->instance->getExpected());
        $this->assertEquals('"arg1", "arg2"', $this->instance->getArguments());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationBaseModel::getMethodName()
     */
    public function testGetMethodName()
    {
        $expected = 'AssertSomethingElse';

        $property = (new \ReflectionClass(AnnotationBaseModel::class))->getProperty('methodName');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getMethodName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationBaseModel::getExpected()
     */
    public function testGetExpected()
    {
        $expected = '"An expected value"';

        $property = (new \ReflectionClass(AnnotationBaseModel::class))->getProperty('expected');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected . ', ', $this->instance->getExpected());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationBaseModel::getArguments()
     */
    public function testGetArguments()
    {
        $expected = '"arg1", "arg2"';

        $property = (new \ReflectionClass(AnnotationBaseModel::class))->getProperty('arguments');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getArguments());
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationBaseModel::getCall()
     */
    public function testGetCall()
    {
        $class = $this->createMock(ClassModelInterface::class);
        $class->method('getName')->willReturn('MyClass');

        $method = $this->createMock(MethodModelInterface::class);
        $method->method('isPublic')
            ->willReturnOnConsecutiveCalls(false, false, false, false, true, true, true, true);
        $method->method('isStatic')
            ->willReturnOnConsecutiveCalls(false, true);
        $method->method('getObjectToUse')->willReturn('$this->instance');
        $method->method('getName')->willReturn('myMethod');
        $method->method('getParentClass')->willReturn($class);

        $property = (new \ReflectionClass(AnnotationBaseModel::class))->getProperty('method');
        $property->setAccessible(true);
        $property->setValue($this->instance, $method);

        $this->assertEquals('$method->invoke($this->instance', $this->instance->getCall());

        $property = (new \ReflectionClass(AnnotationBaseModel::class))->getProperty('arguments');
        $property->setAccessible(true);
        $property->setValue($this->instance, '"arg1", "arg2"');

        $this->assertEquals('$method->invoke($this->instance, ', $this->instance->getCall());
        $this->assertEquals('$this->instance->myMethod(', $this->instance->getCall());
        $this->assertEquals('MyClass::myMethod(', $this->instance->getCall());
    }
}
