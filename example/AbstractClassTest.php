<?php

namespace Test\PHPUnitGenerator\Example\AbstractClass;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Example\AbstractClass;

/**
 * Class AbstractClassTest
 *
 * @covers \PHPUnitGenerator\Example\AbstractClass
 */
class AbstractClassTest extends TestCase
{
    /**
     * The trait/abstract class instance to test
     * @var AbstractClass $instance
     */
    protected $instance;

    /**
     * Build the instance of AbstractClass
     */
    protected function setUp()
    {
        $this->instance = $this->getMockBuilder(AbstractClass::class)
            ->setConstructorArgs(['A simple string', $this->createMock(\PHPUnitGenerator\Example\BaseClass::class), $this->createMock(\ReflectionClass::class), $this->createMock(\DateTime::class), $this->createMock(\HttpRequest::class)])
            ->getMockForAbstractClass();
    }

    /**
     * @covers \PHPUnitGenerator\Example\AbstractClass::__construct()
     */
    public function testConstruct()
    {
        // @todo: Complete this test
        $this->markTestIncomplete();
    }

    /**
     * @covers \PHPUnitGenerator\Example\AbstractClass::simpleAddition()
     */
    public function testSimpleAddition()
    {
        $this->assertEquals(5, AbstractClass::simpleAddition(2, 3));
        $this->assertEquals(0, AbstractClass::simpleAddition());
    }

    /**
     * @covers \PHPUnitGenerator\Example\AbstractClass::setProperty1()
     */
    public function testSetProperty1()
    {
        $expected = true;

        $property = (new \ReflectionClass(AbstractClass::class))->getProperty('property1');
        $property->setAccessible(true);

        $this->instance->setProperty1($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }
}
