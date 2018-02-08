<?php

namespace Test\Company\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Company\Entity\EmployeeCard;
use Company\Entity\Employee;
use DateTime;

/**
 * Class EmployeeCardTest.
 *
 * @covers \Company\Entity\EmployeeCard
 */
class EmployeeCardTest extends TestCase
{
    /**
     * @var EmployeeCard $employeeCard An instance of "EmployeeCard" to test.
     */
    private $employeeCard;

    /**
    * @var Employee|MockObject $employeeMock An mock of "Employee" to use in unit tests.
    */
    private $employeeMock;

    /**
    * @var DateTime|MockObject $dateMock An mock of "DateTime" to use in unit tests.
    */
    private $dateMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->employeeMock = $this->createMock(Employee::class);
        $this->dateMock = $this->createMock(DateTime::class);

        $this->employeeCard = new EmployeeCard($this->employeeMock, $this->dateMock);
    }

    /**
     * @covers \Company\Entity\EmployeeCard::__construct
     */
    public function testConstruct(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Company\Entity\EmployeeCard::getOwner
     */
    public function testGetOwner(): void
    {
        $expected = $this->createMock(Employee::class);

        $property = (new \ReflectionClass(EmployeeCard::class))
            ->getProperty('owner');
        $property->setAccessible(true);
        $property->setValue($this->employeeCard, $expected);

        $this->assertSame($expected, $this->employeeCard->getOwner());
    }

    /**
     * @covers \Company\Entity\EmployeeCard::setOwner
     */
    public function testSetOwner(): void
    {
        $expected = $this->createMock(Employee::class);

        $property = (new \ReflectionClass(EmployeeCard::class))
            ->getProperty('owner');
        $property->setAccessible(true);
        $this->employeeCard->setOwner($expected);

        $this->assertSame($expected, $property->getValue($this->employeeCard));
    }

    /**
     * @covers \Company\Entity\EmployeeCard::getExpirationDate
     */
    public function testGetExpirationDate(): void
    {
        $expected = $this->createMock(DateTime::class);

        $property = (new \ReflectionClass(EmployeeCard::class))
            ->getProperty('expirationDate');
        $property->setAccessible(true);
        $property->setValue($this->employeeCard, $expected);

        $this->assertSame($expected, $this->employeeCard->getExpirationDate());
    }

    /**
     * @covers \Company\Entity\EmployeeCard::setExpirationDate
     */
    public function testSetExpirationDate(): void
    {
        $expected = $this->createMock(DateTime::class);

        $property = (new \ReflectionClass(EmployeeCard::class))
            ->getProperty('expirationDate');
        $property->setAccessible(true);
        $this->employeeCard->setExpirationDate($expected);

        $this->assertSame($expected, $property->getValue($this->employeeCard));
    }
}
