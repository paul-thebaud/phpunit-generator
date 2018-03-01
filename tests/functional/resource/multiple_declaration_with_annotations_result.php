<?php

namespace Test\My\SuperNamespace;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use My\SuperNamespace\AFirstClass;
use My\SuperNamespace\SomeClass;
use My\SuperNamespace\SomeOtherClass;
use AnotherClass;
use My\SuperNamespace\ASecondClass;
use My\SuperNamespace\AFirstTrait;

/**
 * Class multiple_declaration_with_annotations_result.
 *
 * @covers \My\SuperNamespace\AFirstClass
 * @covers \My\SuperNamespace\ASecondClass
 * @covers \My\SuperNamespace\AFirstTrait
 */
class multiple_declaration_with_annotations_result extends TestCase
{
    /**
     * @var AFirstClass $aFirstClass An instance of "AFirstClass" to test.
     */
    private $aFirstClass;

    /**
     * @var ASecondClass $aSecondClass An instance of "ASecondClass" to test.
     */
    private $aSecondClass;

    /**
     * @var AFirstTrait $aFirstTrait An instance of "AFirstTrait" to test.
     */
    private $aFirstTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->aFirstClass = new AFirstClass('John');
        /** @todo Maybe add some arguments to this constructor */
        $this->aSecondClass = $this->getMockBuilder(ASecondClass::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /** @todo Maybe add some arguments to this constructor */
        $this->aFirstTrait = $this->getMockBuilder(AFirstTrait::class)
            ->disableOriginalConstructor()
            ->getMockForTrait();
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::__construct
     */
    public function testConstruct(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::getName
     */
    public function testGetName(): void
    {
        $result = $this->aFirstClass->getName();
        $this->assertSame('John', $result);
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::getAttr1
     */
    public function testGetAttr1(): void
    {
        $expected = $this->createMock(SomeClass::class);

        $property = (new \ReflectionClass($this->aFirstClass))
            ->getProperty('attr1');
        $property->setAccessible(true);
        $property->setValue($this->aFirstClass, $expected);

        $this->assertSame($expected, $this->aFirstClass->getAttr1());
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::getAttr2
     */
    public function testGetAttr2(): void
    {
        $expected = $this->createMock(SomeOtherClass::class);

        $property = (new \ReflectionClass($this->aFirstClass))
            ->getProperty('attr2');
        $property->setAccessible(true);
        $property->setValue(null, $expected);

        $this->assertSame($expected, AFirstClass::getAttr2());
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::getAttr3
     */
    public function testGetAttr3(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \My\SuperNamespace\ASecondClass::doSomething
     */
    public function testDoSomething(): void
    {
        $result = $this->aSecondClass->doSomething();
        $this->assertEquals('I do something!', $result);
    }

    /**
     * @covers \My\SuperNamespace\AFirstTrait::aUsefulMethod
     */
    public function testAUsefulMethod(): void
    {
        $result = $this->aFirstTrait->aUsefulMethod();
        $this->assertEquals('I do something useful!', $result);
    }
}
