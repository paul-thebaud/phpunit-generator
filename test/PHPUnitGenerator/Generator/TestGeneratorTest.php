<?php

namespace Test\PHPUnitGenerator\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Exception\IsInterfaceException;
use PHPUnitGenerator\FileSystem\FileSystemInterface\FileSystemInterface;
use PHPUnitGenerator\Generator\TestGenerator;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Parser\CodeParser;
use PHPUnitGenerator\Parser\DocumentationParser;
use PHPUnitGenerator\Parser\ParserInterface\CodeParserInterface;
use PHPUnitGenerator\Parser\ParserInterface\DocumentationParserInterface;
use PHPUnitGenerator\Renderer\RendererInterface\TestRendererInterface;
use PHPUnitGenerator\Renderer\TwigTestRenderer;

/**
 * Class TestGeneratorTest
 *
 * @covers \PHPUnitGenerator\Generator\TestGenerator
 */
class TestGeneratorTest extends TestCase
{
    /**
     * @var TestGenerator $instance The class instance to test
     */
    protected $instance;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $codeParser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $documentationParser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $testRenderer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileSystem;

    /**
     * Build the instance of TestGenerator
     */
    protected function setUp()
    {
        $this->codeParser          = $this->createMock(CodeParserInterface::class);
        $this->documentationParser = $this->createMock(DocumentationParserInterface::class);
        $this->testRenderer        = $this->createMock(TestRendererInterface::class);
        $this->fileSystem          = $this->createMock(FileSystemInterface::class);
        $this->config              = $this->createMock(ConfigInterface::class);

        $this->instance = new TestGenerator($this->config);

        $this->instance->setCodeParser($this->codeParser);
        $this->instance->setDocumentationParser($this->documentationParser);
        $this->instance->setTestRenderer($this->testRenderer);
        $this->instance->setFileSystem($this->fileSystem);
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::__construct()
     */
    public function testConstruct()
    {
        $instance = new TestGenerator($this->config);

        $reflection = new \ReflectionClass(TestGenerator::class);
        $codeParser = $reflection->getProperty('codeParser');
        $codeParser->setAccessible(true);
        $documentationParser = $reflection->getProperty('documentationParser');
        $documentationParser->setAccessible(true);
        $testRenderer = $reflection->getProperty('testRenderer');
        $testRenderer->setAccessible(true);

        $this->assertInstanceOf(CodeParser::class, $codeParser->getValue($instance));
        $this->assertInstanceOf(DocumentationParser::class, $documentationParser->getValue($instance));
        $this->assertInstanceOf(TwigTestRenderer::class, $testRenderer->getValue($instance));
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::generate()
     */
    public function testGenerate()
    {
        $methodModel1     = $this->createMock(MethodModelInterface::class);
        $methodModel2     = $this->createMock(MethodModelInterface::class);
        $annotationModel1 = $this->createMock(AnnotationModelInterface::class);
        $annotationModel2 = $this->createMock(AnnotationModelInterface::class);

        $this->config->expects($this->exactly(2))->method('getOption')
            ->with(ConfigInterface::OPTION_INTERFACE)
            ->willReturnOnConsecutiveCalls(true, false);

        $classModel = $this->createMock(ClassModelInterface::class);
        $classModel->expects($this->once())->method('isInterface')
            ->willReturnOnConsecutiveCalls(true);
        $classModel->expects($this->once())->method('getMethods')
            ->willReturn([$methodModel1, $methodModel2]);
        $methodModel1->expects($this->once())->method('setAnnotations')
            ->with([$annotationModel1]);
        $methodModel2->expects($this->once())->method('setAnnotations')
            ->with([$annotationModel2]);

        $this->codeParser->expects($this->exactly(2))->method('parse')
            ->with('Some PHP code')->willReturn($classModel);
        $this->documentationParser->expects($this->exactly(2))->method('parse')
            ->withConsecutive([$methodModel1], [$methodModel2])
            ->willReturnOnConsecutiveCalls([$annotationModel1], [$annotationModel2]);
        $this->testRenderer->expects($this->once())->method('render')
            ->with($classModel)->willReturn('Some tests PHP code');

        $this->assertEquals('Some tests PHP code', $this->instance->generate('Some PHP code'));

        $this->expectException(IsInterfaceException::class);
        $this->expectExceptionMessage(IsInterfaceException::TEXT);
        $this->instance->generate('Some PHP code');
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::writeFile()
     */
    public function testWriteFile()
    {
        // @todo: Complete this test
        $this->markTestIncomplete();
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::writeDir()
     */
    public function testWriteDir()
    {
        // @todo: Complete this test
        $this->markTestIncomplete();
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::setCodeParser()
     */
    public function testSetCodeParser()
    {
        $expected = $this->createMock(\PHPUnitGenerator\Parser\ParserInterface\CodeParserInterface::class);

        $property = (new \ReflectionClass(TestGenerator::class))->getProperty('codeParser');
        $property->setAccessible(true);

        $this->instance->setCodeParser($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::setDocumentationParser()
     */
    public function testSetDocumentationParser()
    {
        $expected = $this->createMock(\PHPUnitGenerator\Parser\ParserInterface\DocumentationParserInterface::class);

        $property = (new \ReflectionClass(TestGenerator::class))->getProperty('documentationParser');
        $property->setAccessible(true);

        $this->instance->setDocumentationParser($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::setTestRenderer()
     */
    public function testSetTestRenderer()
    {
        $expected = $this->createMock(\PHPUnitGenerator\Renderer\RendererInterface\TestRendererInterface::class);

        $property = (new \ReflectionClass(TestGenerator::class))->getProperty('testRenderer');
        $property->setAccessible(true);

        $this->instance->setTestRenderer($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }
}
