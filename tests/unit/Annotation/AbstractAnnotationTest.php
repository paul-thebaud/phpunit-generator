<?php

namespace UnitTests\PhpUnitGen\Annotation;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AbstractAnnotation;
use PhpUnitGen\Annotation\AssertAnnotation;

/**
 * Class AbstractAnnotationTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers \PhpUnitGen\Annotation\AbstractAnnotation
 */
class AbstractAnnotationTest extends TestCase
{
    /**
     * @var AbstractAnnotation $annotation
     */
    private $annotation;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->annotation = new AssertAnnotation();
    }

    /**
     * @covers \PhpUnitGen\Annotation\AbstractAnnotation::setName()
     * @covers \PhpUnitGen\Annotation\AbstractAnnotation::getName()
     * @covers \PhpUnitGen\Annotation\AbstractAnnotation::setLine()
     * @covers \PhpUnitGen\Annotation\AbstractAnnotation::getLine()
     * @covers \PhpUnitGen\Annotation\AbstractAnnotation::setStringContent()
     * @covers \PhpUnitGen\Annotation\AbstractAnnotation::getStringContent()
     */
    public function testSetterGetter(): void
    {
        $this->assertNull($this->annotation->getStringContent());

        $this->annotation->setName('assertEquals');
        $this->annotation->setLine(5);
        $this->annotation->setStringContent('"\'expected value\'"');

        $this->assertSame('assertEquals', $this->annotation->getName());
        $this->assertSame(5, $this->annotation->getLine());
        $this->assertSame('"\'expected value\'"', $this->annotation->getStringContent());
    }
}
