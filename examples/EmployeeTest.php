<?php

namespace Test\Company\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Company\Entity\Employee;

/**
 * Class EmployeeTest.
 *
 * @covers \Company\Entity\Employee
 */
class EmployeeTest extends TestCase
{
    /**
     * @var Employee $employee An instance of "Employee" to test.
     */
    private $employee;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->employee = new Employee(1, 'John', 1234567890);
    }

    /**
     * @covers \Company\Entity\Employee::__construct
     */
    public function testConstruct(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    /**
     * @covers \Company\Entity\Employee::getName
     */
    public function testGetName(): void
    {
        $expected = "a string to test";

        $property = (new \ReflectionClass(Employee::class))
            ->getProperty('name');
        $property->setAccessible(true);
        $property->setValue($this->employee, $expected);

        $this->assertSame($expected, $this->employee->getName());
    }

    /**
     * @covers \Company\Entity\Employee::setName
     */
    public function testSetName(): void
    {
        $expected = "a string to test";

        $property = (new \ReflectionClass(Employee::class))
            ->getProperty('name');
        $property->setAccessible(true);
        $this->employee->setName($expected);

        $this->assertSame($expected, $property->getValue($this->employee));
    }

    /**
     * @covers \Company\Entity\Employee::getCellPhone
     */
    public function testGetCellPhone(): void
    {
        $expected = 42;

        $property = (new \ReflectionClass(Employee::class))
            ->getProperty('phone');
        $property->setAccessible(true);
        $property->setValue($this->employee, $expected);

        $this->assertSame($expected, $this->employee->getCellPhone());
    }

    /**
     * @covers \Company\Entity\Employee::setCellphonePhone
     */
    public function testSetCellphonePhone(): void
    {
        $expected = 42;

        $property = (new \ReflectionClass(Employee::class))
            ->getProperty('phone');
        $property->setAccessible(true);
        $this->employee->setCellphonePhone($expected);

        $this->assertSame($expected, $property->getValue($this->employee));
    }

    /**
     * @covers \Company\Entity\Employee::toString
     */
    public function testToString(): void
    {
        $result = $this->employee->toString();
        $this->assertInternalType('string', $result);
        $this->assertSame('John: 1234567890', $result);
    }
}
