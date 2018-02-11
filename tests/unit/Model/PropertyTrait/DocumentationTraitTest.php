<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AssertAnnotation;
use PhpUnitGen\Model\ClassModel;

/**
 * Class DocumentationTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\DocumentationTrait
 */
class DocumentationTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\DocumentationTrait::setDocumentation()
     * @covers \PhpUnitGen\Model\PropertyTrait\DocumentationTrait::getDocumentation()
     * @covers \PhpUnitGen\Model\PropertyTrait\DocumentationTrait::addAnnotation()
     */
    public function testMethods(): void
    {
        $class = new ClassModel();

        $annotationsProperty = (new \ReflectionClass($class))
            ->getProperty('annotations');
        $annotationsProperty->setAccessible(true);

        $class->setDocumentation('/** a doc block */');
        $this->assertSame('/** a doc block */', $class->getDocumentation());

        $annotation1 = new AssertAnnotation();
        $annotation2 = new AssertAnnotation();

        $this->assertSame(0, $annotationsProperty->getValue($class)->count());

        $class->addAnnotation($annotation1);
        $class->addAnnotation($annotation2);

        $this->assertSame(2, $annotationsProperty->getValue($class)->count());
        $this->assertSame($annotation1, $annotationsProperty->getValue($class)->first());
        $this->assertSame($annotation2, $annotationsProperty->getValue($class)->get(1));
    }
}
