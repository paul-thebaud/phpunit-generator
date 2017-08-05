<?php

namespace Test\PHPUnitGenerator\Model\ArgumentModel;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Generator\FixedValueGenerator;
use PHPUnitGenerator\Model\ArgumentModel;
use PHPUnitGenerator\Model\MethodModel;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Model\ModelInterface\TypeInterface;

/**
 * Class ArgumentModelTest
 *
 * @covers \PHPUnitGenerator\Model\ArgumentModel
 */
class ArgumentModelTest extends TestCase
{
    /**
     * @var ArgumentModel $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of ArgumentModel
     */
    protected function setUp()
    {
        $this->instance = new ArgumentModel(
            $this->createMock(MethodModelInterface::class),
            'argument'
        );
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(MethodModelInterface::class, $this->instance->getParentMethod());
        $this->assertInternalType('string', $this->instance->getName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::getName()
     */
    public function testGetName()
    {
        $property = (new \ReflectionClass(ArgumentModel::class))->getProperty('name');
        $property->setAccessible(true);

        $property->setValue($this->instance, 'argument');
        $this->assertEquals('argument', $this->instance->getName());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::getType()
     */
    public function testGetType()
    {
        $property = (new \ReflectionClass(ArgumentModel::class))->getProperty('type');
        $property->setAccessible(true);

        $property->setValue($this->instance, 'type');
        $this->assertEquals('type', $this->instance->getType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::setType()
     */
    public function testSetType()
    {
        $this->instance->setType(TypeInterface::TYPE_INT);
        $this->assertEquals(TypeInterface::TYPE_INT, $this->instance->getType());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::getNullable()
     */
    public function testGetNullable()
    {
        $property = (new \ReflectionClass(ArgumentModel::class))->getProperty('nullable');
        $property->setAccessible(true);

        $property->setValue($this->instance, true);
        $this->assertEquals(true, $this->instance->getNullable());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::setNullable()
     */
    public function testSetNullable()
    {
        $this->instance->setNullable(true);
        $this->assertEquals(true, $this->instance->getNullable());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::getParentMethod()
     */
    public function testGetParentMethod()
    {
        $property = (new \ReflectionClass(ArgumentModel::class))->getProperty('name');
        $property->setAccessible(true);

        $methodModel = $this->createMock(MethodModelInterface::class);
        $property->setValue($this->instance, $methodModel);
        $this->assertEquals($methodModel, $this->instance->getParentMethod());
    }

    /**
     * @covers \PHPUnitGenerator\Model\ArgumentModel::generateValue()
     */
    public function testGenerateValue()
    {
        $this->instance->setType(TypeInterface::TYPE_INT);
        $this->assertEquals(FixedValueGenerator::generateValue(TypeInterface::TYPE_INT), $this->instance->generateValue());
        $this->instance->setType(TypeInterface::TYPE_CALLABLE);
        $this->assertEquals('/** @todo: A callable value */', $this->instance->generateValue());
    }
}
