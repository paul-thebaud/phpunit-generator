<?php

namespace Test\PHPUnitGenerator\Parser;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Model\AnnotationBaseModel;
use PHPUnitGenerator\Model\AnnotationGetModel;
use PHPUnitGenerator\Model\AnnotationSetModel;
use PHPUnitGenerator\Model\ClassModel;
use PHPUnitGenerator\Model\MethodModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Parser\DocumentationParser;

/**
 * Class DocumentationParserTest
 *
 * @covers \PHPUnitGenerator\Parser\DocumentationParser
 */
class DocumentationParserTest extends TestCase
{
    /**
     * @var DocumentationParser $instance The class instance to test
     */
    protected $instance;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * Build the instance of DocumentationParser
     */
    protected function setUp()
    {
        $this->config = $this->createMock(ConfigInterface::class);

        $this->instance = new DocumentationParser($this->config);
    }

    /**
     * @covers \PHPUnitGenerator\Parser\DocumentationParser::__construct()
     */
    public function testConstruct()
    {
        $config = (new \ReflectionClass(DocumentationParser::class))
            ->getProperty('config');
        $config->setAccessible(true);

        $this->assertEquals($this->config, $config->getValue($this->instance));
    }

    /**
     * @covers \PHPUnitGenerator\Parser\DocumentationParser::parse()
     */
    public function testParse()
    {
        $methodModel = new MethodModel($this->createMock(ClassModelInterface::class), "myMethod");
        $documentation = <<<TEXT
/*
 * A method with a description
 * @param int \$param1 A first param
 * @param int \$param2 A second param
 * @return int
 * @PHPUnitGen\AssertEquals(5:{2, 3})
 * @PHPUnitGen\AssertSomething(0)
 * @PHPUnitGen\Get()
 * @PHPUnitGen\Set()
 */
TEXT;
        $methodModel->setDocumentation($documentation);

        $annotations = $this->instance->parse($methodModel);

        $this->assertInstanceOf(AnnotationBaseModel::class, $annotations[0]);
        $this->assertInstanceOf(AnnotationBaseModel::class, $annotations[1]);
        $this->assertInstanceOf(AnnotationGetModel::class, $annotations['get']);
        $this->assertInstanceOf(AnnotationSetModel::class, $annotations['set']);

        $this->assertEquals('assertEquals', $annotations[0]->getMethodName());
        $this->assertEquals('5, ', $annotations[0]->getExpected());
        $this->assertEquals('2, 3', $annotations[0]->getArguments());
        $this->assertEquals('assertSomething', $annotations[1]->getMethodName());
        $this->assertEquals('0, ', $annotations[1]->getExpected());
        $this->assertEquals('', $annotations[1]->getArguments());
        $this->assertEquals($methodModel, $annotations[0]->getParentMethod());
        $this->assertEquals($methodModel, $annotations[1]->getParentMethod());
        $this->assertEquals($methodModel, $annotations['set']->getParentMethod());
        $this->assertEquals($methodModel, $annotations['get']->getParentMethod());

        $this->config->expects($this->once())->method('getOption')
            ->with(ConfigInterface::OPTION_AUTO)->willReturn(true);
        $classModel = new ClassModel('BasicClass');
        $classModel->setProperties(['property']);
        $getMethod = new MethodModel($classModel, "getProperty");

        $annotations = $this->instance->parse($getMethod);
        $this->assertCount(1, $annotations);
        $this->assertInstanceOf(AnnotationGetModel::class, $annotations['get']);
    }

    /**
     * @covers \PHPUnitGenerator\Parser\DocumentationParser::parseContent()
     */
    public function testParseContent()
    {
        $method = (new \ReflectionClass(DocumentationParser::class))
            ->getMethod('parseContent');
        $method->setAccessible(true);

        $this->assertEquals("", $method->invoke($this->instance, "My content"));
        $this->assertEquals("A clean content", $method->invoke($this->instance, "(A clean content)"));
        $this->assertEquals(
            "\t{A clean \t\ncontent} with special chars\t",
            $method->invoke($this->instance, "(\t{A clean \t\ncontent} with special chars\t)\t\n")
        );
        $this->assertEquals(
            "\t{A clean \t\ncontent} with (some (parenthesis)\t  \n)\t",
            $method->invoke($this->instance, "(\t{A clean \t\ncontent} with (some (parenthesis)\t  \n)\t)\t\n")
        );
        $this->assertEquals(
            "\t{A clean \t\ncontent} with (too much opening (((parenthesis)\t  \n)\t",
            $method->invoke($this->instance, "(\t{A clean \t\ncontent} with (too much opening (((parenthesis)\t  \n)\t)\t\n")
        );
        $this->assertEquals(
            "\t{A clean \t\ncontent} with too less closing ) parenthesis",
            $method->invoke($this->instance, "(\t{A clean \t\ncontent} with too less closing ) parenthesis)\t  \n")
        );
    }
}
