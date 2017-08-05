<?php

namespace Test\PHPUnitGenerator\Config\Config;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Config\Config;

/**
 * Class ConfigTest
 *
 * @covers \PHPUnitGenerator\Config\Config
 */
class ConfigTest extends TestCase
{
    /**
     * @var Config $instance The class instance to test
     */
    protected $instance;

    /**
     * @var array
     */
    protected $options;

    /**
     * Build the instance of Config
     */
    protected function setUp()
    {
        $this->options = [
            'option1' => 'value1',
            'option2' => 'value2',
        ];

        $this->instance = new Config($this->options);
    }

    /**
     * @covers \PHPUnitGenerator\Config\Config::__construct()
     */
    public function testConstruct()
    {
        $this->assertEquals($this->options, $this->instance->getOptions());
    }

    /**
     * @covers \PHPUnitGenerator\Config\Config::getOptions()
     */
    public function testGetOptions()
    {
        $property = (new \ReflectionClass(Config::class))->getProperty('options');
        $property->setAccessible(true);

        $property->setValue($this->instance, ['options']);
        $this->assertEquals(['options'], $this->instance->getOptions());
    }

    /**
     * @covers \PHPUnitGenerator\Config\Config::getOption()
     */
    public function testGetOption()
    {
        $this->assertEquals('value1', $this->instance->getOption('option1'));
        $this->assertEquals('value2', $this->instance->getOption('option2'));
        $this->assertNull($this->instance->getOption('option3'));
        $this->assertEquals('default', $this->instance->getOption('option3', 'default'));
    }
}
