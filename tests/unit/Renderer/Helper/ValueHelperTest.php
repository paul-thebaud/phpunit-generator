<?php

namespace UnitTests\PhpUnitGen\Renderer;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\PropertyInterface\TypeInterface;
use PhpUnitGen\Renderer\Helper\ValueHelper;

/**
 * Class ValueHelperTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Renderer\Helper\ValueHelper
 */
class ValueHelperTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Renderer\Helper\ValueHelper::invoke()
     */
    public function testInvokeOnMultipleTypes(): void
    {
        $parametersHelper = new ValueHelper();

        $this->assertSame('"a string to test"', $parametersHelper->invoke());
        $this->assertSame('$this->createMock(\\DateTime::class)', $parametersHelper->invoke(TypeInterface::OBJECT));
        $this->assertSame('true', $parametersHelper->invoke(TypeInterface::BOOL));
        $this->assertSame('42', $parametersHelper->invoke(TypeInterface::INT));
        $this->assertSame('42.42', $parametersHelper->invoke(TypeInterface::FLOAT));
        $this->assertSame('["a", "strings", "array"]', $parametersHelper->invoke(TypeInterface::ARRAY));
        $this->assertSame('["a", "strings", "array"]', $parametersHelper->invoke(TypeInterface::ITERABLE));
        $this->assertSame('function(): void {/* A callable */}', $parametersHelper->invoke(TypeInterface::CALLABLE));
        $this->assertSame('"a string to test"', $parametersHelper->invoke(TypeInterface::STRING));
        $this->assertSame('"a string to test"', $parametersHelper->invoke(TypeInterface::MIXED));

        $this->assertSame('/** @todo Insert a value with a correct type here */', $parametersHelper->invoke(-10));

        $this->assertSame('$this->createMock(MyClass::class)',
            $parametersHelper->invoke(TypeInterface::CUSTOM, 'MyClass'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Custom type must have a custom class to mock');
        $parametersHelper->invoke(TypeInterface::CUSTOM);
    }
}
