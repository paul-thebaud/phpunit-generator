<?php

namespace Company\Entity;

/**
 * Class Employee.
 *
 * @PhpUnitGen\construct(["1", "'John'", "1234567890"])
 */
class Employee extends AbstractEntity
{
    private $name, $phone;

    public function __construct(int $id, string $name, int $phone)
    {
        parent::__construct($id);
        $this->name  = $name;
        $this->phone = $phone;
    }

    /**
     * @PhpUnitGen\get()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @PhpUnitGen\set()
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @PhpUnitGen\get("phone")
     */
    public function getCellPhone(): int
    {
        return $this->phone;
    }

    /**
     * @PhpUnitGen\set("phone")
     */
    public function setCellphonePhone(int $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @PhpUnitGen\assertNotNull()
     * @PhpUnitGen\assertInternalType("'string'")
     * @PhpUnitGen\assertSame("'John: 1234567890'")
     */
    public function toString(): string
    {
        return $this->name . ': ' . $this->phone;
    }
}
