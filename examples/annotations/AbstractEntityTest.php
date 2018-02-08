<?php

namespace Test\Company\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Company\Entity\AbstractEntity;

/**
 * Class AbstractEntityTest.
 *
 * @covers \Company\Entity\AbstractEntity
 */
class AbstractEntityTest extends TestCase
{
    /**
     * @var AbstractEntity $abstractEntity An instance of "AbstractEntity" to test.
     */
    private $abstractEntity;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->abstractEntity = $this->getMockBuilder(AbstractEntity::class)
            ->setConstructorArgs([1])
            ->getMockForAbstractClass();
    }

    /**
     * @covers \Company\Entity\AbstractEntity::__construct
     */
    public function testConstruct(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Company\Entity\AbstractEntity::getId
     */
    public function testGetId(): void
    {
        $expected = 42;

        $property = (new \ReflectionClass(AbstractEntity::class))
            ->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->abstractEntity, $expected);

        $this->assertSame($expected, $this->abstractEntity->getId());
    }
}
