<?php

namespace Test\PHPUnitGenerator\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Exception\InvalidTypeException;
use PHPUnitGenerator\Generator\FixedValueGenerator;
use PHPUnitGenerator\Model\ModelInterface\TypeInterface;

/**
 * Class FixedValueGeneratorTest
 *
 * @covers \PHPUnitGenerator\Generator\FixedValueGenerator
 */
class FixedValueGeneratorTest extends TestCase
{
    /**
     * @covers \PHPUnitGenerator\Generator\FixedValueGenerator::generateValue()
     */
    public function testGenerateValue()
    {
        $this->assertEquals('true', FixedValueGenerator::generateValue(TypeInterface::TYPE_BOOL));
        $this->assertEquals('1', FixedValueGenerator::generateValue(TypeInterface::TYPE_INT));
        $this->assertEquals('1.5', FixedValueGenerator::generateValue(TypeInterface::TYPE_FLOAT));
        $this->assertEquals('\'A simple string\'', FixedValueGenerator::generateValue(TypeInterface::TYPE_MIXED));
        $this->assertEquals('\'A simple string\'', FixedValueGenerator::generateValue(TypeInterface::TYPE_STRING));
        $this->assertEquals('[\'a\', \'simple\', \'array\']', FixedValueGenerator::generateValue(TypeInterface::TYPE_ARRAY));
        $this->assertEquals('$this->createMock(\AClass::class)', FixedValueGenerator::generateValue('AClass'));

        $this->expectException(InvalidTypeException::class);
        FixedValueGenerator::generateValue(TypeInterface::TYPE_CALLABLE);
    }
}
