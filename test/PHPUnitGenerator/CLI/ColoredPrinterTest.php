<?php

namespace Test\PHPUnitGenerator\CLI\ColoredPrinter;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\CLI\ColoredPrinter;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;

/**
 * Class ColoredPrinterTest
 *
 * @covers \PHPUnitGenerator\CLI\ColoredPrinter
 */
class ColoredPrinterTest extends TestCase
{
    /**
     * @var ColoredPrinter $instance The class instance to test
     */
    protected $instance;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * Build the instance of ColoredPrinter
     */
    protected function setUp()
    {
        $this->config = $this->createMock(ConfigInterface::class);

        $this->instance = new ColoredPrinter($this->config);

        $this->mock = $this->getMockBuilder(ColoredPrinter::class)
            ->setConstructorArgs([$this->config])
            ->setMethods(['printMessage'])
            ->getMock();
    }

    /**
     * @covers \PHPUnitGenerator\CLI\ColoredPrinter::__construct()
     */
    public function testConstruct()
    {
        $property = (new \ReflectionClass(ColoredPrinter::class))->getProperty('config');
        $property->setAccessible(true);

        $this->assertEquals($this->config, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\CLI\ColoredPrinter::error()
     */
    public function testError()
    {
        $this->mock->expects($this->once())->method('printMessage')
            ->with('41', 'message', ['arg1', 'arg2']);

        $this->mock->error('message', 'arg1', 'arg2');
    }

    /**
     * @covers \PHPUnitGenerator\CLI\ColoredPrinter::warning()
     */
    public function testWarning()
    {
        $this->mock->expects($this->once())->method('printMessage')
            ->with('43', 'message', ['arg1', 'arg2']);

        $this->mock->warning('message', 'arg1', 'arg2');
    }

    /**
     * @covers \PHPUnitGenerator\CLI\ColoredPrinter::success()
     */
    public function testSuccess()
    {
        $this->mock->expects($this->once())->method('printMessage')
            ->with('42', 'message', ['arg1', 'arg2']);

        $this->mock->success('message', 'arg1', 'arg2');
    }

    /**
     * @covers \PHPUnitGenerator\CLI\ColoredPrinter::info()
     */
    public function testInfo()
    {
        $this->mock->expects($this->once())->method('printMessage')
            ->with('0', 'message', ['arg1', 'arg2']);

        $this->mock->info('message', 'arg1', 'arg2');
    }

    /**
     * @covers \PHPUnitGenerator\CLI\ColoredPrinter::printMessage()
     */
    public function testPrintMessage()
    {

        $method = (new \ReflectionClass(ColoredPrinter::class))->getMethod('printMessage');
        $method->setAccessible(true);

        $this->config->expects($this->exactly(5))->method('getOption')
            ->withConsecutive(
                [ConfigInterface::OPTION_PRINT, null],
                [ConfigInterface::OPTION_PRINT, null],
                [ConfigInterface::OPTION_NO_COLOR, null],
                [ConfigInterface::OPTION_PRINT, null],
                [ConfigInterface::OPTION_NO_COLOR, null]
            )->willReturnOnConsecutiveCalls(null, true, null, true, true);

        $this->expectOutputString("\033[42mMessage: arg\033[0m\n\nMessage: arg\n\n");

        $method->invoke($this->instance, '42', 'Message: %s', ['arg']);
        $method->invoke($this->instance, '42', 'Message: %s', ['arg']);
        $method->invoke($this->instance, '42', 'Message: %s', ['arg']);
    }
}
