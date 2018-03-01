<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\AssertAnnotation;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Class AssertAnnotationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\AssertAnnotation
 */
class AssertAnnotationTest extends TestCase
{
    /**
     * @var AssertAnnotation $annotation
     */
    private $annotation;

    /**
     * @var FunctionModelInterface|MockObject $function
     */
    private $function;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->function = $this->createMock(FunctionModelInterface::class);

        $this->annotation = new AssertAnnotation();
        $this->annotation->setParentNode($this->function);
    }

    /**
     * @covers \PhpUnitGen\Annotation\AssertAnnotation::getType()
     */
    public function testGetType(): void
    {
        $this->assertSame(AnnotationInterface::TYPE_ASSERT, $this->annotation->getType());
    }

    /**
     * @covers \PhpUnitGen\Annotation\AssertAnnotation::compile()
     */
    public function testJsonDecodeThrowException(): void
    {
        $this->annotation->setStringContent('{ invalid json');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"assertion" annotation content is invalid (invalid JSON content)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\AssertAnnotation::compile()
     */
    public function testJsonDecodeReturnAnArray(): void
    {
        $this->annotation->setStringContent('{"invalid": "json content"}');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"assertion" annotation content is invalid (expected value must be a string)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\AssertAnnotation::compile()
     */
    public function testValidContent(): void
    {
        $this->annotation->setStringContent('"\'myExpectedValue\'"');

        $this->annotation->compile();

        $this->assertSame('\'myExpectedValue\'', $this->annotation->getExpected());
    }
}
