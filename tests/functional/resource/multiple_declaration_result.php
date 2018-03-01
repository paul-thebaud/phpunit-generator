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
 * Class multiple_declaration_result.
 *
 * @covers \My\SuperNamespace\AFirstClass
 * @covers \My\SuperNamespace\ASecondClass
 * @covers \My\SuperNamespace\AFirstTrait
 */
class multiple_declaration_result extends TestCase
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
        /** @todo Maybe add some arguments to this constructor */
        $this->aFirstClass = new AFirstClass();
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
     * @covers \My\SuperNamespace\AFirstClass::getAttr1
     */
    public function testGetAttr1(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::getAttr2
     */
    public function testGetAttr2(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
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
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \My\SuperNamespace\AFirstTrait::aUsefulMethod
     */
    public function testAUsefulMethod(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }
}
