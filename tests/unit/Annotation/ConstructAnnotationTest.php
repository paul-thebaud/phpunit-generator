<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Annotation\ConstructAnnotation;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ModelInterface\ClassModelInterface;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Class ConstructAnnotationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\ConstructAnnotation
 */
class ConstructAnnotationTest extends TestCase
{
    /**
     * @var ConstructAnnotation $annotation
     */
    private $annotation;

    /**
     * @var ClassModelInterface|MockObject $class
     */
    private $class;

    /**
     * @var PhpFileModelInterface|MockObject $phpFile
     */
    private $phpFile;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->class = $this->createMock(ClassModelInterface::class);
        $this->phpFile = $this->createMock(PhpFileModelInterface::class);

        $this->annotation = new ConstructAnnotation();
        $this->annotation->setParentNode($this->class);
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::getType()
     */
    public function testGetType(): void
    {
        $this->assertSame(AnnotationInterface::TYPE_CONSTRUCT, $this->annotation->getType());
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     */
    public function testJsonDecodeThrowException(): void
    {
        $this->annotation->setStringContent('{ invalid json');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"construct" annotation content is invalid (invalid JSON content)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::validate()
     */
    public function testJsonDecodeInvalidArraySize(): void
    {
        $this->annotation->setStringContent('');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"construct" annotation content is invalid (must contains parameters array, and maybe a class)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::validate()
     */
    public function testJsonDecodeInvalidClassName(): void
    {
        $this->annotation->setStringContent('{"value": "Containing an array value"}, "a string value"');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"construct" annotation content is invalid (constructor class must be a string)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::validate()
     */
    public function testJsonDecodeInvalidParametersValueIsString(): void
    {
        $this->annotation->setStringContent('"a string value", "a string value"');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"construct" annotation content is invalid (constructor parameters must be a array of string)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::validate()
     */
    public function testJsonDecodeInvalidParametersAreNotStrings(): void
    {
        $this->annotation->setStringContent('"a string value", [{"value": "Containing an array value"}]');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"construct" annotation content is invalid (constructor parameters must be a array of string)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     */
    public function testWithAbsoluteMockedClass(): void
    {
        $this->class->expects($this->once())->method('getParentNode')
            ->with()->willReturn($this->phpFile);

        $this->annotation->setStringContent('"\\\\Absolute\\\\MyClass", []');

        $this->phpFile->expects($this->once())->method('addConcreteUse')
            ->with('\Absolute\MyClass', 'MyClass');

        $this->annotation->compile();

        $this->assertSame('MyClass', $this->annotation->getClass());
        $this->assertSame([], $this->annotation->getParameters());
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     */
    public function testWithNotAbsoluteMockedClassAndNamespace(): void
    {
        $this->class->expects($this->once())->method('getParentNode')
            ->with()->willReturn($this->phpFile);

        $this->annotation->setStringContent('"NotAbsolute\\\\MyClass", []');

        $this->phpFile->expects($this->once())->method('getNamespaceString')
            ->with()->willReturn('My\CurrentNamespace');

        $this->phpFile->expects($this->once())->method('addConcreteUse')
            ->with('My\CurrentNamespace\NotAbsolute\MyClass', 'MyClass');

        $this->annotation->compile();

        $this->assertSame('MyClass', $this->annotation->getClass());
        $this->assertSame([], $this->annotation->getParameters());
    }

    /**
     * @covers \PhpUnitGen\Annotation\ConstructAnnotation::compile()
     */
    public function testWithoutCustomClass(): void
    {
        $this->class->expects($this->never())->method('getParentNode');

        $this->annotation->setStringContent('["\'param1\'", "\'param2\'"]');

        $this->phpFile->expects($this->never())->method('getNamespaceString');

        $this->phpFile->expects($this->never())->method('addConcreteUse');

        $this->annotation->compile();

        $this->assertNull($this->annotation->getClass());
        $this->assertSame(['\'param1\'', '\'param2\''], $this->annotation->getParameters());
    }
}
