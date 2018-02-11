<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Class GetAnnotationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\GetAnnotation
 */
class GetAnnotationTest extends TestCase
{
    /**
     * @var GetAnnotation $annotation
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

        $this->annotation = new GetAnnotation();
        $this->annotation->setParentNode($this->function);
    }

    /**
     * @covers \PhpUnitGen\Annotation\GetAnnotation::getType()
     */
    public function testGetType(): void
    {
        $this->assertSame(AnnotationInterface::TYPE_GET, $this->annotation->getType());
    }

    /**
     * @covers \PhpUnitGen\Annotation\GetAnnotation::compile()
     */
    public function testNotStringContent(): void
    {
        $this->function->expects($this->exactly(2))->method('getName')
            ->with()->willReturn('getMyProperty');

        $this->annotation->compile();

        $this->assertSame('myProperty', $this->annotation->getProperty());

        $this->annotation->setStringContent('');
        $this->annotation->compile();

        $this->assertSame('myProperty', $this->annotation->getProperty());
    }

    /**
     * @covers \PhpUnitGen\Annotation\GetAnnotation::compile()
     */
    public function testJsonDecodeThrowException(): void
    {
        $this->annotation->setStringContent('{ invalid json');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"getter" annotation content is invalid (invalid JSON content)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\GetAnnotation::compile()
     */
    public function testJsonDecodeReturnAnArray(): void
    {
        $this->annotation->setStringContent('{"invalid": "json content"}');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"getter" annotation content is invalid (property name must be a string)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\GetAnnotation::compile()
     */
    public function testValidContent(): void
    {
        $this->annotation->setStringContent('"myCustomProperty"');

        $this->annotation->compile();

        $this->assertSame('myCustomProperty', $this->annotation->getProperty());
    }
}
