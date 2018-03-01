<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\ParamsAnnotation;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;

/**
 * Class ParamsAnnotationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\ParamsAnnotation
 */
class ParamsAnnotationTest extends TestCase
{
    /**
     * @var ParamsAnnotation $annotation
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

        $this->annotation = new ParamsAnnotation();
        $this->annotation->setParentNode($this->function);
    }

    /**
     * @covers \PhpUnitGen\Annotation\ParamsAnnotation::getType()
     */
    public function testGetType(): void
    {
        $this->assertSame(AnnotationInterface::TYPE_PARAMS, $this->annotation->getType());
    }

    /**
     * @covers \PhpUnitGen\Annotation\ParamsAnnotation::compile()
     */
    public function testJsonDecodeThrowException(): void
    {
        $this->annotation->setStringContent('{ invalid json');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"params" annotation content is invalid (invalid JSON content)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ParamsAnnotation::compile()
     */
    public function testJsonDecodeReturnAnInvalidArray(): void
    {
        $this->annotation->setStringContent('{"invalid": "json content"}');

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('"params" annotation content is invalid (must contains strings only)');

        $this->annotation->compile();
    }

    /**
     * @covers \PhpUnitGen\Annotation\ParamsAnnotation::compile()
     */
    public function testValidContent(): void
    {
        $this->annotation->setStringContent('"\'param1\'", "\'param2\'"');

        $this->annotation->compile();

        $this->assertSame(['\'param1\'', '\'param2\''], $this->annotation->getParameters());
    }
}
