<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Annotation\MockAnnotation;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Class MockAnnotationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\MockAnnotation
 */
class MockAnnotationTest extends TestCase
{
    /**
     * @var MockAnnotation $annotation
     */
    private $annotation;

    /**
     * @var PhpFileModelInterface|MockObject $phpFile
     */
    private $phpFile;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->phpFile = $this->createMock(PhpFileModelInterface::class);

        $this->annotation = new MockAnnotation();
        $this->annotation->setParentNode($this->phpFile);
    }

    /**
     * @covers \PhpUnitGen\Annotation\MockAnnotation::getType()
     */
    public function testGetType(): void
    {
        $this->assertSame(AnnotationInterface::TYPE_MOCK, $this->annotation->getType());
    }

    /**
     * @covers \PhpUnitGen\Annotation\MockAnnotation::compile()
     */
    public function testJsonDecodeThrowException(): void
    {
        $this->annotation->setStringContent('{ invalid json');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"mock" annotation content is invalid (invalid JSON content)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\MockAnnotation::compile()
     * @covers \PhpUnitGen\Annotation\MockAnnotation::validate()
     */
    public function testJsonDecodeInvalidArraySize(): void
    {
        $this->annotation->setStringContent('"Containing only one element"');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"mock" annotation content is invalid (must contains the class to mock and the property name)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\MockAnnotation::compile()
     * @covers \PhpUnitGen\Annotation\MockAnnotation::validate()
     */
    public function testJsonDecodeInvalidArrayValues(): void
    {
        $this->annotation->setStringContent('{"value": "Containing an array value"}, "a string value"');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"mock" annotation content is invalid (class and property name must be string)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\MockAnnotation::compile()
     */
    public function testAbsoluteMockedClass(): void
    {
        $this->annotation->setStringContent('"\\\\Absolute\\\\MyClass", "myDateMock"');

        $this->phpFile->expects($this->once())->method('addConcreteUse')
            ->with('\Absolute\MyClass', 'MyClass');

        $this->annotation->compile();

        $this->assertSame('MyClass', $this->annotation->getClass());
        $this->assertSame('myDateMock', $this->annotation->getProperty());
    }

    /**
     * @covers \PhpUnitGen\Annotation\MockAnnotation::compile()
     */
    public function testNotAbsoluteMockedClassAndNamespace(): void
    {
        $this->annotation->setStringContent('"NotAbsolute\\\\MyClass", "myDateMock"');

        $this->phpFile->expects($this->once())->method('getNamespaceString')
            ->with()->willReturn('My\CurrentNamespace');

        $this->phpFile->expects($this->once())->method('addConcreteUse')
            ->with('My\CurrentNamespace\NotAbsolute\MyClass', 'MyClass');

        $this->annotation->compile();

        $this->assertSame('MyClass', $this->annotation->getClass());
        $this->assertSame('myDateMock', $this->annotation->getProperty());
    }
}
