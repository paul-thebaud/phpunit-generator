<?php

namespace Test\PHPUnitGenerator\Model\MethodModel;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Generator\FixedValueGenerator;
use PHPUnitGenerator\Model\MethodModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ArgumentModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ModifierInterface;
use PHPUnitGenerator\Model\ModelInterface\TypeInterface;

/**
 * Class MethodModelTest
 *
 * @covers \PHPUnitGenerator\Model\MethodModel
 */
class MethodModelTest extends TestCase
{
    /**
     * @var MethodModel $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of MethodModel
     */
    protected function setUp()
    {
        $this->instance = new MethodModel(
            $this->createMock(ClassModelInterface::class),
            'method'
        );
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ClassModelInterface::class, $this->instance->getParentClass());
        $this->assertInternalType('string', $this->instance->getName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getName()
     */
    public function testGetName()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('name');
        $property->setAccessible(true);

        $property->setValue($this->instance, 'method');
        $this->assertEquals('method', $this->instance->getName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getVisibility()
     */
    public function testGetVisibility()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('visibility');
        $property->setAccessible(true);

        $property->setValue($this->instance, MethodModelInterface::VISIBILITY_PRIVATE);
        $this->assertEquals(MethodModelInterface::VISIBILITY_PRIVATE, $this->instance->getVisibility());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setVisibility()
     */
    public function testSetVisibility()
    {
        $this->instance->setVisibility(MethodModelInterface::VISIBILITY_PRIVATE);
        $this->assertEquals(MethodModelInterface::VISIBILITY_PRIVATE, $this->instance->getVisibility());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getModifiers()
     */
    public function testGetModifiers()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('modifiers');
        $property->setAccessible(true);

        $property->setValue($this->instance, [ModifierInterface::MODIFIER_STATIC]);
        $this->assertEquals([ModifierInterface::MODIFIER_STATIC], $this->instance->getModifiers());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::hasModifier()
     */
    public function testHasModifier()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('modifiers');
        $property->setAccessible(true);

        $property->setValue($this->instance, [ModifierInterface::MODIFIER_STATIC]);
        $this->assertTrue($this->instance->hasModifier(ModifierInterface::MODIFIER_STATIC));
        $this->assertFalse($this->instance->hasModifier('something'));
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setModifiers()
     */
    public function testSetModifiers()
    {
        $this->instance->setModifiers([ModifierInterface::MODIFIER_STATIC]);
        $this->assertEquals([ModifierInterface::MODIFIER_STATIC], $this->instance->getModifiers());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getArguments()
     */
    public function testGetArguments()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('arguments');
        $property->setAccessible(true);

        $arguments = [$this->createMock(ArgumentModelInterface::class)];
        $property->setValue($this->instance, $arguments);
        $this->assertEquals($arguments, $this->instance->getArguments());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setArguments()
     */
    public function testSetArguments()
    {
        $arguments = [$this->createMock(ArgumentModelInterface::class)];
        $this->instance->setArguments($arguments);
        $this->assertEquals($arguments, $this->instance->getArguments());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getReturnType()
     */
    public function testGetReturnType()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('returnType');
        $property->setAccessible(true);

        $property->setValue($this->instance, TypeInterface::TYPE_INT);
        $this->assertEquals(TypeInterface::TYPE_INT, $this->instance->getReturnType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setReturnType()
     */
    public function testSetReturnType()
    {
        $this->instance->setReturnType(TypeInterface::TYPE_INT);
        $this->assertEquals(TypeInterface::TYPE_INT, $this->instance->getReturnType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getReturnNullable()
     */
    public function testGetReturnNullable()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('returnNullable');
        $property->setAccessible(true);

        $property->setValue($this->instance, true);
        $this->assertEquals(true, $this->instance->getReturnNullable());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setReturnNullable()
     */
    public function testSetReturnNullable()
    {
        $this->instance->setReturnNullable(true);
        $this->assertEquals(true, $this->instance->getReturnNullable());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getDocumentation()
     */
    public function testGetDocumentation()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('documentation');
        $property->setAccessible(true);

        $property->setValue($this->instance, 'documentation');
        $this->assertEquals('documentation', $this->instance->getDocumentation());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setDocumentation()
     */
    public function testSetDocumentation()
    {
        $this->instance->setDocumentation('documentation');
        $this->assertEquals('documentation', $this->instance->getDocumentation());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getAnnotations()
     */
    public function testGetAnnotations()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('annotations');
        $property->setAccessible(true);

        $annotations = [$this->createMock(AnnotationModelInterface::class)];
        $property->setValue($this->instance, $annotations);
        $this->assertEquals($annotations, $this->instance->getAnnotations());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::setAnnotations()
     */
    public function testSetAnnotations()
    {
        $annotations = [$this->createMock(AnnotationModelInterface::class)];
        $this->instance->setAnnotations($annotations);
        $this->assertEquals($annotations, $this->instance->getAnnotations());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getParentClass()
     */
    public function testGetParentClass()
    {
        $property = (new \ReflectionClass(MethodModel::class))->getProperty('class');
        $property->setAccessible(true);

        $class = $this->createMock(ClassModelInterface::class);
        $property->setValue($this->instance, $class);
        $this->assertEquals($class, $this->instance->getParentClass());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getTestName()
     */
    public function testGetTestName()
    {
        $this->assertEquals('testMethod', $this->instance->getTestName());
        $this->instance = new MethodModel($this->createMock(ClassModelInterface::class), '__construct');
        $this->assertEquals('testConstruct', $this->instance->getTestName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::isPublic()
     */
    public function testIsPublic()
    {
        $this->assertTrue($this->instance->isPublic());
        $this->instance->setVisibility(MethodModel::VISIBILITY_PRIVATE);
        $this->assertFalse($this->instance->isPublic());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::isAbstract()
     */
    public function testIsAbstract()
    {
        $this->assertFalse($this->instance->isAbstract());
        $this->instance->setModifiers([ModifierInterface::MODIFIER_ABSTRACT]);
        $this->assertTrue($this->instance->isAbstract());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::isStatic()
     */
    public function testIsStatic()
    {
        $this->assertFalse($this->instance->isStatic());
        $this->instance->setModifiers([ModifierInterface::MODIFIER_STATIC]);
        $this->assertTrue($this->instance->isStatic());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::getObjectToUse()
     */
    public function testGetObjectToUse()
    {
        $this->instance->setModifiers([ModifierInterface::MODIFIER_STATIC]);
        $this->assertEquals('null', $this->instance->getObjectToUse());
        $this->instance->setModifiers([]);
        $this->assertEquals('$this->instance', $this->instance->getObjectToUse());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::generateValue()
     */
    public function testGenerateValue()
    {
        $this->instance->setReturnType(TypeInterface::TYPE_INT);
        $this->assertEquals(FixedValueGenerator::generateValue(TypeInterface::TYPE_INT), $this->instance->generateValue());
        $this->instance->setReturnType(TypeInterface::TYPE_CALLABLE);
        $this->assertEquals('/** @todo: A callable value */', $this->instance->generateValue());
    }

    /**
     * @covers \PHPUnitGenerator\Model\MethodModel::generateValues()
     */
    public function testGenerateValues()
    {
        $argument1 = $this->createMock(ArgumentModelInterface::class);
        $argument1->expects($this->once())->method('generateValue')
            ->willReturn('value1');
        $argument2 = $this->createMock(ArgumentModelInterface::class);
        $argument2->expects($this->once())->method('generateValue')
            ->willReturn('value2');

        $this->instance->setArguments([$argument1, $argument2]);

        $this->assertEquals('value1, value2', $this->instance->generateValues());
    }
}
