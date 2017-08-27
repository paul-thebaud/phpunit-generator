<?php

namespace Test\PHPUnitGenerator\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\CLI\Application;
use PHPUnitGenerator\CLI\CLIInterface\PrinterInterface;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Exception\FileExistsException;
use PHPUnitGenerator\Exception\InvalidCodeException;
use PHPUnitGenerator\Exception\InvalidRegexException;
use PHPUnitGenerator\Exception\IsInterfaceException;
use PHPUnitGenerator\FileSystem\FileSystemInterface\FileSystemInterface;
use PHPUnitGenerator\FileSystem\LocalFileSystem;
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
        $fileSystem = $reflection->getProperty('fileSystem');
        $fileSystem->setAccessible(true);

        $this->assertInstanceOf(CodeParser::class, $codeParser->getValue($instance));
        $this->assertInstanceOf(DocumentationParser::class, $documentationParser->getValue($instance));
        $this->assertInstanceOf(TwigTestRenderer::class, $testRenderer->getValue($instance));
        $this->assertInstanceOf(LocalFileSystem::class, $fileSystem->getValue($instance));
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
        $this->fileSystem->expects($this->exactly(2))->method('fileExists')
            ->with(__FILE__)->willReturn(true);
        $this->fileSystem->expects($this->once())->method('read')
            ->with('in file')->willReturn('code');
        $this->fileSystem->expects($this->once())->method('mkDir')
            ->with(__DIR__);
        $this->fileSystem->expects($this->once())->method('write')
            ->with(__FILE__, 'test code');
        $this->config->expects($this->exactly(2))->method('getOption')
            ->with(ConfigInterface::OPTION_OVERWRITE)->willReturnOnConsecutiveCalls(true, false);

        $printer = $this->createMock(PrinterInterface::class);
        $printer->expects($this->once())->method('info')
            ->with('"%s" tests generated', 'in file');

        $printerProperty = (new \ReflectionClass(Application::class))->getProperty('printer');
        $printerProperty->setAccessible(true);
        $printerProperty->setValue($printer);

        $mock = $this->getMockBuilder(TestGenerator::class)
            ->setConstructorArgs([$this->config])
            ->setMethods(['generate'])
            ->getMock();
        $mock->expects($this->once())->method('generate')
            ->with('code')->willReturn('test code');

        $mock->setCodeParser($this->codeParser);
        $mock->setDocumentationParser($this->documentationParser);
        $mock->setTestRenderer($this->testRenderer);
        $mock->setFileSystem($this->fileSystem);

        $this->assertEquals(1, $mock->writeFile('in file', __FILE__));

        $this->expectException(FileExistsException::class);
        $mock->writeFile('in file', __FILE__);
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::writeDir()
     */
    public function testWriteDir()
    {
        $mock = $this->getMockBuilder(TestGenerator::class)
            ->setConstructorArgs([$this->config])
            ->setMethods(['checkRegexValidity', 'writeFile'])
            ->getMock();

        $mock->setCodeParser($this->codeParser);
        $mock->setDocumentationParser($this->documentationParser);
        $mock->setTestRenderer($this->testRenderer);
        $mock->setFileSystem($this->fileSystem);

        $mock->expects($this->exactly(4))->method('writeFile')
            ->withConsecutive(
                ['/in dir/file1.php', '/out dir/file1Test.php'],
                ['/in dir/dir/file2.phtml', '/out dir/dir/file2Test.php']
            )->willReturnOnConsecutiveCalls(
                1,
                1,
                $this->throwException(new IsInterfaceException('error')),
                $this->throwException(new InvalidCodeException())
            );
        $mock->expects($this->exactly(4))->method('checkRegexValidity')
            ->withConsecutive([ConfigInterface::OPTION_INCLUDE], [ConfigInterface::OPTION_EXCLUDE]);

        $this->fileSystem->expects($this->exactly(2))->method('mkDir')
            ->with('/out dir/');
        $this->fileSystem->expects($this->exactly(2))->method('filterFiles')
            ->with(
                ['/in dir/file1.php', '/in dir/dir/file2.phtml'],
                'include regex',
                'exclude regex'
            )->willReturn(['/in dir/file1.php', '/in dir/dir/file2.phtml']);
        $this->fileSystem->expects($this->exactly(2))->method('getFiles')
            ->withConsecutive(['/in dir/'], ['/in dir'])
            ->willReturn(['/in dir/file1.php', '/in dir/dir/file2.phtml']);

        $this->config->expects($this->exactly(6))->method('getOption')
            ->withConsecutive(
                [ConfigInterface::OPTION_INCLUDE],
                [ConfigInterface::OPTION_EXCLUDE],
                [ConfigInterface::OPTION_INCLUDE],
                [ConfigInterface::OPTION_EXCLUDE],
                [ConfigInterface::OPTION_IGNORE],
                [ConfigInterface::OPTION_IGNORE]
            )
            ->willReturnOnConsecutiveCalls(
                'include regex',
                'exclude regex',
                'include regex',
                'exclude regex',
                true,
                false
            );

        $this->assertEquals(2, $mock->writeDir('/in dir/', '/out dir'));

        $printer = $this->createMock(PrinterInterface::class);
        $printer->expects($this->once())->method('warning')
            ->with("An error occurred during tests creation (for \"%s\"):\n\n\t%s", '/in dir/file1.php', 'error');

        $printerProperty = (new \ReflectionClass(Application::class))->getProperty('printer');
        $printerProperty->setAccessible(true);
        $printerProperty->setValue($printer);

        $this->expectException(InvalidCodeException::class);
        $mock->writeDir('/in dir', '/out dir/');
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

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::setFileSystem()
     */
    public function testSetFileSystem()
    {
        $expected = $this->createMock(\PHPUnitGenerator\FileSystem\FileSystemInterface\FileSystemInterface::class);

        $property = (new \ReflectionClass(TestGenerator::class))->getProperty('fileSystem');
        $property->setAccessible(true);

        $this->instance->setFileSystem($expected);

        $this->assertEquals($expected, $property->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Generator\TestGenerator::checkRegexValidity()
     */
    public function testCheckRegexValidity()
    {
        $method = (new \ReflectionClass(TestGenerator::class))->getMethod('checkRegexValidity');
        $method->setAccessible(true);

        $this->config->expects($this->exactly(5))->method('getOption')
            ->with(ConfigInterface::OPTION_INCLUDE)
            ->willReturnOnConsecutiveCalls(null, '/.*.php/', '/.*.php/', 'invalid', 'invalid');

        $method->invoke($this->instance, ConfigInterface::OPTION_INCLUDE);
        $method->invoke($this->instance, ConfigInterface::OPTION_INCLUDE);

        $this->expectException(InvalidRegexException::class);
        $method->invoke($this->instance, ConfigInterface::OPTION_INCLUDE);
    }
}
