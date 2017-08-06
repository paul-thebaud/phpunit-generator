<?php

namespace Test\PHPUnitGenerator\Example\BaseClass;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Example\BaseClass;

/**
 * Class BaseClassTest
 *
 * @covers \PHPUnitGenerator\Example\BaseClass
 */
class BaseClassTest extends TestCase
{
    /**
     * @var BaseClass $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of BaseClass
     */
    protected function setUp()
    {
        $this->instance = new BaseClass();
    }

    /**
     * @covers \PHPUnitGenerator\Example\BaseClass::__construct()
     */
    public function testConstruct()
    {
        // @todo: Complete this test
        $this->markTestIncomplete();
    }

    /**
     * @covers \PHPUnitGenerator\Example\BaseClass::simpleAddition()
     */
    public function testSimpleAddition()
    {
        $this->assertEquals(5, BaseClass::simpleAddition(2, 3));
        $this->assertEquals(0, BaseClass::simpleAddition());
    }

    /**
     * @covers \PHPUnitGenerator\Example\BaseClass::setProperty1()
     */
    public function testSetProperty1()
    {
        $expected = 'A simple string';

        $property = (new \ReflectionClass(BaseClass::class))->getProperty('property1');
        $property->setAccessible(true);

        $this->instance->setProperty1($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Example\BaseClass::getProperty2()
     */
    public function testGetProperty2()
    {
        $method = (new \ReflectionClass(get_class($this->instance)))->getMethod('getProperty2');
        $method->setAccessible(true);
        $expected = 'A simple string';

        $property = (new \ReflectionClass(BaseClass::class))->getProperty('property2');
        $property->setAccessible(true);
        $property->setValue($this->instance, $expected);

        $this->assertEquals($expected, $method->invoke($this->instance));
    }
}
