<?php

namespace Test\My\SuperNamespace;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use My\SuperNamespace\AFirstClass;
use My\SuperNamespace\SomeClass;
use My\SuperNamespace\SomeOtherClass;
use AnotherClass;
use My\SuperNamespace\MyInterface;

/**
 * Class auto_detect_result.
 *
 * @author John Doe <john.doe@example.com>.
 * @copyright 2017-2018 John Doe <john.doe@example.com>.
 * @license https://opensource.org/licenses/MIT The MIT license.
 * @link https://github.com/john-doe/my-awesome-project
 * @since File available since Release 1.0.0
 *
 * @covers \My\SuperNamespace\AFirstClass
 * @covers \My\SuperNamespace\MyInterface
 */
class auto_detect_result extends TestCase
{
    /**
     * @var AFirstClass $aFirstClass An instance of "AFirstClass" to test.
     */
    private $aFirstClass;

    /**
     * @var MyInterface $myInterface An instance of "MyInterface" to test.
     */
    private $myInterface;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->aFirstClass = new AFirstClass();
        /** @todo Instantiate a class that implements MyInterface */
        // $this->myInterface = ...
    }

    /**
     * Covers the global function "doSomethingGlobal".
     */
    public function testDoSomethingGlobal(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
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
     * @covers \My\SuperNamespace\AFirstClass::setAttr1
     */
    public function testSetAttr1(): void
    {
        $expected = $this->createMock(SomeClass::class);

        $property = (new \ReflectionClass($this->aFirstClass))
            ->getProperty('attr1');
        $property->setAccessible(true);
        $this->aFirstClass->setAttr1($expected);

        $this->assertSame($expected, $property->getValue($this->aFirstClass));
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
     * @covers \My\SuperNamespace\AFirstClass::setAttr2
     */
    public function testSetAttr2(): void
    {
        $expected = $this->createMock(SomeOtherClass::class);

        $property = (new \ReflectionClass($this->aFirstClass))
            ->getProperty('attr2');
        $property->setAccessible(true);
        AFirstClass::setAttr2($expected);

        $this->assertSame($expected, $property->getValue(null));
    }

    /**
     * @covers \My\SuperNamespace\AFirstClass::getAttr3
     */
    public function testGetAttr3(): void
    {
        $expected = $this->createMock(AnotherClass::class);

        $property = (new \ReflectionClass($this->aFirstClass))
            ->getProperty('attr3');
        $property->setAccessible(true);
        $property->setValue($this->aFirstClass, $expected);

        $this->assertSame($expected, $this->aFirstClass->getAttr3());
    }

    /**
     * @covers \My\SuperNamespace\MyInterface::doSomething
     */
    public function testDoSomething(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \My\SuperNamespace\MyInterface::doSomethingAwesome
     */
    public function testDoSomethingAwesome(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }
}
