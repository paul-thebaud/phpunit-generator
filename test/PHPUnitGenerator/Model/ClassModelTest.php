<?php

namespace Test\PHPUnitGenerator\Model\ClassModel;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Model\ClassModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ModifierInterface;

/**
 * Class ClassModelTest
 *
 * @covers \PHPUnitGenerator\Model\ClassModel
 */
class ClassModelTest extends TestCase
{
    /**
     * @var ClassModel $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of ClassModel
     */
    protected function setUp()
    {
        $this->instance = new ClassModel('MyClass');
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInternalType('string', $this->instance->getName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getNamespaceName()
     */
    public function testGetNamespaceName()
    {
        $expected = 'My\\Namespace';

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('namespaceName');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getNamespaceName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::setNamespaceName()
     */
    public function testSetNamespaceName()
    {
        $expected = 'My\\Namespace';

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('namespaceName');
        $property->setAccessible(true);

        $this->instance->setNamespaceName($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getName()
     */
    public function testGetName()
    {
        $expected = 'MyClass';

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('name');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getType()
     */
    public function testGetType()
    {
        $expected = ClassModelInterface::TYPE_CLASS;

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('type');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::setType()
     */
    public function testSetType()
    {
        $expected = ClassModelInterface::TYPE_CLASS;

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('type');
        $property->setAccessible(true);

        $this->instance->setType($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getModifier()
     */
    public function testGetModifier()
    {
        $expected = ModifierInterface::MODIFIER_ABSTRACT;

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('modifier');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getModifier());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::setModifier()
     */
    public function testSetModifier()
    {
        $expected = ModifierInterface::MODIFIER_ABSTRACT;

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('modifier');
        $property->setAccessible(true);

        $this->instance->setModifier($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getProperties()
     */
    public function testGetProperties()
    {
        $expected = ['property1', 'property2'];

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('properties');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getProperties());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::setProperties()
     */
    public function testSetProperties()
    {
        $expected = ['property1', 'property2'];

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('properties');
        $property->setAccessible(true);

        $this->instance->setProperties($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::hasProperty()
     */
    public function testHasProperty()
    {
        $this->instance->setProperties(['property1', 'property2']);

        $this->assertTrue($this->instance->hasProperty('property1'));
        $this->assertTrue($this->instance->hasProperty('property2'));
        $this->assertFalse($this->instance->hasProperty('property3'));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getMethods()
     */
    public function testGetMethods()
    {
        $expected = [
            $this->createMock(MethodModelInterface::class),
            $this->createMock(MethodModelInterface::class)
        ];

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('methods');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getMethods());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getMethod()
     */
    public function testGetMethod()
    {
        $method1 = $this->createMock(MethodModelInterface::class);
        $method1->method('getName')->willReturn('method1');
        $method2 = $this->createMock(MethodModelInterface::class);
        $method2->method('getName')->willReturn('method2');

        $this->instance->setMethods([$method1, $method2]);

        $this->assertEquals($method1, $this->instance->getMethod('method1'));
        $this->assertEquals($method2, $this->instance->getMethod('method2'));
        $this->assertNull($this->instance->getMethod('method3'));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::setMethods()
     */
    public function testSetMethods()
    {
        $expected = [
            $this->createMock(MethodModelInterface::class),
            $this->createMock(MethodModelInterface::class)
        ];

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('methods');
        $property->setAccessible(true);

        $this->instance->setMethods($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::hasMethod()
     */
    public function testHasMethod()
    {
        $method1 = $this->createMock(MethodModelInterface::class);
        $method1->method('getName')->willReturn('method1');
        $method2 = $this->createMock(MethodModelInterface::class);
        $method2->method('getName')->willReturn('method2');

        $this->instance->setMethods([$method1, $method2]);

        $this->assertTrue($this->instance->hasMethod('method1'));
        $this->assertTrue($this->instance->hasMethod('method2'));
        $this->assertFalse($this->instance->hasMethod('method3'));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getTestsAnnotations()
     */
    public function testGetTestsAnnotations()
    {
        $expected = [
            $this->createMock(AnnotationModelInterface::class),
            $this->createMock(AnnotationModelInterface::class)
        ];

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('testsAnnotations');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $this->instance->getTestsAnnotations());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::setTestsAnnotations()
     */
    public function testSetTestsAnnotations()
    {
        $expected = [
            $this->createMock(AnnotationModelInterface::class),
            $this->createMock(AnnotationModelInterface::class)
        ];

        $property = (new \ReflectionClass(ClassModel::class))->getProperty('testsAnnotations');
        $property->setAccessible(true);

        $this->instance->setTestsAnnotations($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::getCompleteName()
     */
    public function testGetCompleteName()
    {
        $this->instance->setNamespaceName('My\\Namespace');

        $this->assertEquals('My\\Namespace\\MyClass', $this->instance->getCompleteName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::isInterface()
     */
    public function testIsInterface()
    {
        $this->assertFalse($this->instance->isInterface());
        $this->instance->setType(ClassModelInterface::TYPE_INTERFACE);
        $this->assertTrue($this->instance->isInterface());
        $this->instance->setType(ClassModelInterface::TYPE_TRAIT);
        $this->assertFalse($this->instance->isInterface());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::isTrait()
     */
    public function testIsTrait()
    {
        $this->assertFalse($this->instance->isTrait());
        $this->instance->setType(ClassModelInterface::TYPE_TRAIT);
        $this->assertTrue($this->instance->isTrait());
        $this->instance->setType(ClassModelInterface::TYPE_INTERFACE);
        $this->assertFalse($this->instance->isTrait());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ClassModel::isAbstract()
     */
    public function testIsAbstract()
    {
        $this->assertFalse($this->instance->isAbstract());
        $this->instance->setModifier(ModifierInterface::MODIFIER_ABSTRACT);
        $this->assertTrue($this->instance->isAbstract());
        $this->instance->setModifier(ModifierInterface::MODIFIER_STATIC);
        $this->assertFalse($this->instance->isAbstract());
    }
}
