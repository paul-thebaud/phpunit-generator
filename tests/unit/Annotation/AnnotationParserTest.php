<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationFactory;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\AnnotationLexer;
use PhpUnitGen\Annotation\AnnotationParser;
use PhpUnitGen\Annotation\AnnotationRegister;
use PhpUnitGen\Annotation\TokenConsumer;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\PhpFileModel;

/**
 * Class AnnotationParserTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\AnnotationParser
 * @covers     \PhpUnitGen\Annotation\AnnotationLexer
 * @covers     \PhpUnitGen\Annotation\TokenConsumer
 * @covers     \PhpUnitGen\Annotation\AnnotationRegister
 */
class AnnotationParserTest extends TestCase
{
    /**
     * @var AnnotationParser $annotationParser
     */
    private $annotationParser;

    /**
     * @var PhpFileModel $phpFile
     */
    private $phpFile;

    /**
     * @var ClassModel $class
     */
    private $class;

    /**
     * @var FunctionModel $function
     */
    private $function;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->phpFile = new PhpFileModel();

        $this->class = new ClassModel();
        $this->class->setParentNode($this->phpFile);

        $this->function = new FunctionModel();
        $this->function->setParentNode($this->class);

        $this->annotationParser = new AnnotationParser(
            new AnnotationLexer(),
            new TokenConsumer(new AnnotationFactory()),
            new AnnotationRegister()
        );
    }

    public function testInvalidAnnotationNamespace(): void
    {
        $this->annotationParser->invoke($this->function, '/** @PhpUnitGenerator\assertTrue */');

        $assertAnnotations = $this->function->getAssertAnnotations();
        $this->assertCount(0, $assertAnnotations);
    }

    public function testAnnotationWithoutContent(): void
    {
        $this->annotationParser->invoke($this->function, '/** @Pug\assertTrue */');

        $assertAnnotations = $this->function->getAssertAnnotations();
        $this->assertCount(1, $assertAnnotations);
        $this->assertSame('assertTrue', $assertAnnotations->first()->getName());
        $this->assertNull($assertAnnotations->first()->getStringContent());
    }

    public function testAnnotationEmptyContent(): void
    {
        $this->annotationParser->invoke($this->function, '/** @Pug\assertTrue() */');

        $assertAnnotations = $this->function->getAssertAnnotations();
        $this->assertCount(1, $assertAnnotations);
        $this->assertSame('assertTrue', $assertAnnotations->first()->getName());
        $this->assertSame('', $assertAnnotations->first()->getStringContent());
    }

    public function testUnclosedAnnotationContent(): void
    {
        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('An annotation content is not closed (you probably forget to close a parenthesis or a quote)');

        $this->annotationParser->invoke($this->function, '/** @Pug\assertTrue( */');
    }

    public function testUnclosedAnnotationContentAndNewAnnotationBegin(): void
    {
        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('An annotation content is not closed (you probably forget to close a parenthesis or a quote)');

        $this->annotationParser->invoke($this->function, '/** @Pug\assertTrue( @Pug\assertNotNull */');
    }

    public function testMultipleAnnotationsWithoutContent(): void
    {
        $this->annotationParser->invoke($this->function, '/** @Pug\assertTrue @Pug\assertNotNull */');

        $assertAnnotations = $this->function->getAssertAnnotations();
        $this->assertCount(2, $assertAnnotations);
        $this->assertSame('assertTrue', $assertAnnotations->first()->getName());
        $this->assertNull($assertAnnotations->first()->getStringContent());
        $this->assertSame('assertNotNull', $assertAnnotations->get(1)->getName());
        $this->assertNull($assertAnnotations->get(1)->getStringContent());
    }

    public function testAnnotationContentWithMultipleString(): void
    {
        $this->annotationParser->invoke(
            $this->function,
            '/** @Pug\mock("a string \" with \'quote and \backslash", "a string with ((parenthesis)") */'
        );

        $mockAnnotations = $this->function->getMockAnnotations();
        $this->assertCount(1, $mockAnnotations);
        $this->assertSame(
            '"a string \" with \'quote and \backslash", "a string with ((parenthesis)"',
            $mockAnnotations->first()->getStringContent()
        );
    }

    public function testAnnotationContentWithParenthesis(): void
    {
        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"assertion" annotation content is invalid (invalid JSON content)');

        $this->annotationParser->invoke(
            $this->function,
            '/** @Pug\assertEquals(("a string inside parenthesis")) */'
        );
    }

    public function testAnnotationContentStringSingleQuoted(): void
    {
        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"assertion" annotation content is invalid (invalid JSON content)');

        $this->annotationParser->invoke(
            $this->function,
            '/** @Pug\assertEquals(\'a string inside parenthesis\') */'
        );
    }

    public function testAnnotationContentMultiLinesAndAsterisksRemoved(): void
    {
        $this->annotationParser->invoke(
            $this->function,
            "/** @Pug\\mock(\n *    \"a string\",\n *    \"a string\") */"
        );

        $mockAnnotations = $this->function->getMockAnnotations();
        $this->assertCount(1, $mockAnnotations);
        $this->assertSame(
            "\n     \"a string\",\n     \"a string\"",
            $mockAnnotations->first()->getStringContent()
        );
    }

    public function testAnnotationContentMultiLinesAndAsterisksInNotRemoved(): void
    {
        $this->annotationParser->invoke(
            $this->function,
            "/** @Pug\\mock(\n *    \"a *string*\",\n *    \"a string\") */"
        );

        $mockAnnotations = $this->function->getMockAnnotations();
        $this->assertCount(1, $mockAnnotations);
        $this->assertSame(
            "\n     \"a *string*\",\n     \"a string\"",
            $mockAnnotations->first()->getStringContent()
        );
    }

    public function testAnnotationContentAfterSpace(): void
    {
        $this->annotationParser->invoke(
            $this->function,
            "/** @Pug\\assertEquals     (\"a string\") */"
        );

        $assertAnnotations = $this->function->getAssertAnnotations();
        $this->assertCount(1, $assertAnnotations);
        $this->assertSame(
            '"a string"',
            $assertAnnotations->first()->getStringContent()
        );
    }

    public function testAnnotationNoContentBecauseOnNewLine(): void
    {
        $this->annotationParser->invoke(
            $this->function,
            "/** @Pug\\assertNull   \n *  (\"a string\") */"
        );

        $assertAnnotations = $this->function->getAssertAnnotations();
        $this->assertCount(1, $assertAnnotations);
        $this->assertNull($assertAnnotations->first()->getStringContent());
    }

    public function testAnnotationsRegisteringOnClass(): void
    {
        $annotationsProperty = (new \ReflectionClass($this->class))->getProperty('annotations');
        $annotationsProperty->setAccessible(true);

        $this->annotationParser->invoke($this->class, '/** @Pug\assertNull @Pug\set @Pug\get @Pug\construct([]) 
        @Pug\construct(["params"]) @Pug\mock("\\\\DateTime", "date") @Pug\mock("MyClass", "obj") @Pug\params("param1") */');

        $annotations = $annotationsProperty->getValue($this->class);
        $this->assertCount(2, $annotations);
        $this->assertSame(AnnotationInterface::TYPE_CONSTRUCT, $annotations->get(0)->getType());
        $this->assertSame(AnnotationInterface::TYPE_CONSTRUCT, $annotations->get(1)->getType());

        $this->assertSame(AnnotationInterface::TYPE_MOCK, $this->phpFile->getMockAnnotations()->get(0)->getType());
        $this->assertSame('date', $this->phpFile->getMockAnnotations()->get(0)->getProperty());
        $this->assertSame(AnnotationInterface::TYPE_MOCK, $this->phpFile->getMockAnnotations()->get(1)->getType());
        $this->assertSame('obj', $this->phpFile->getMockAnnotations()->get(1)->getProperty());
    }

    public function testAnnotationsRegisteringOnGlobalFunction(): void
    {
        $this->function->setIsGlobal(true);

        $annotationsProperty = (new \ReflectionClass($this->function))->getProperty('annotations');
        $annotationsProperty->setAccessible(true);

        $this->annotationParser->invoke($this->function, '/** @Pug\assertNull @Pug\assertFalse @Pug\set @Pug\get @Pug\construct([]) 
        @Pug\construct(["params"]) @Pug\mock("\\\\DateTime", "date") @Pug\mock("MyClass", "obj") @Pug\params("param1") */');

        $annotations = $annotationsProperty->getValue($this->function);
        $this->assertCount(5, $annotations);
        $this->assertSame('assertNull', $annotations->get(0)->getName());
        $this->assertSame('assertFalse', $annotations->get(1)->getName());
        $this->assertSame('mock', $annotations->get(2)->getName());
        $this->assertSame('date', $annotations->get(2)->getProperty());
        $this->assertSame('mock', $annotations->get(3)->getName());
        $this->assertSame('obj', $annotations->get(3)->getProperty());
        $this->assertSame('params', $annotations->get(4)->getName());
        $this->assertSame(['param1'], $annotations->get(4)->getParameters());
    }

    public function testAnnotationsRegisteringOnNormalFunction(): void
    {
        $this->function->setIsGlobal(false);

        $annotationsProperty = (new \ReflectionClass($this->function))->getProperty('annotations');
        $annotationsProperty->setAccessible(true);

        $this->annotationParser->invoke($this->function, '/** @Pug\assertNull @Pug\assertFalse @Pug\set @Pug\get @Pug\construct([]) 
        @Pug\construct(["params"]) @Pug\mock("\\\\DateTime", "date") @Pug\mock("MyClass", "obj") @Pug\params("param1") */');

        $annotations = $annotationsProperty->getValue($this->function);
        $this->assertCount(7, $annotations);
        $this->assertSame('assertNull', $annotations->get(0)->getName());
        $this->assertSame('assertFalse', $annotations->get(1)->getName());
        $this->assertSame('set', $annotations->get(2)->getName());
        $this->assertSame('get', $annotations->get(3)->getName());
        $this->assertSame('mock', $annotations->get(4)->getName());
        $this->assertSame('date', $annotations->get(4)->getProperty());
        $this->assertSame('mock', $annotations->get(5)->getName());
        $this->assertSame('obj', $annotations->get(5)->getProperty());
        $this->assertSame('params', $annotations->get(6)->getName());
        $this->assertSame(['param1'], $annotations->get(6)->getParameters());
    }

    public function testParseInvalidToken(): void
    {
        $parseMethod = (new \ReflectionClass($this->annotationParser))
            ->getMethod('parse');
        $parseMethod->setAccessible(true);

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('A token of value "my_invalid_value" has an invalid type');

        $parseMethod->invoke($this->annotationParser, -10, 'my_invalid_value');
    }
}
