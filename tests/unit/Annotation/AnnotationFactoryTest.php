<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AnnotationFactory;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;

/**
 * Class AnnotationFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Annotation\AnnotationFactory
 */
class AnnotationFactoryTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Annotation\AnnotationFactory::invoke()
     */
    public function testEachCase(): void
    {
        $factory = new AnnotationFactory();

        $this->assertSame(AnnotationInterface::TYPE_GET, $factory->invoke('@Pug\\get', 1)->getType());
        $this->assertSame(AnnotationInterface::TYPE_SET, $factory->invoke('@Pug\\set', 1)->getType());
        $this->assertSame(AnnotationInterface::TYPE_CONSTRUCT, $factory->invoke('@Pug\\construct', 1)->getType());
        $this->assertSame(AnnotationInterface::TYPE_MOCK, $factory->invoke('@Pug\\mock', 1)->getType());
        $this->assertSame(AnnotationInterface::TYPE_PARAMS, $factory->invoke('@Pug\\params', 1)->getType());

        $assertAnnotation = $factory->invoke('@Pug\\assertEquals', 1);
        $this->assertSame(1, $assertAnnotation->getLine());
        $this->assertSame(AnnotationInterface::TYPE_ASSERT, $assertAnnotation->getType());
        $this->assertSame('assertEquals', $assertAnnotation->getName());

        $this->expectException(AnnotationParseException::class);
        $this->expectExceptionMessage('Annotation of name "invalidAnnotationName" is unknown');

        $factory->invoke('@Pug\\invalidAnnotationName', 1);
    }
}
