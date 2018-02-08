<?php

namespace Company\Entity;

/**
 * Class EmployeeCard.
 *
 * @PhpUnitGen\mock("Employee", "employeeMock")
 * @PhpUnitGen\mock("\\DateTime", "dateMock")
 *
 * @PhpUnitGen\construct([
 *     "$this->employeeMock",
 *     "$this->dateMock"
 * ])
 */
class EmployeeCard
{
    private $owner, $expirationDate;

    public function __construct(Employee $owner, \DateTime $expirationDate)
    {
        $this->owner          = $owner;
        $this->expirationDate = $expirationDate;
    }

    /**
     * @PhpUnitGen\get
     */
    public function getOwner(): Employee
    {
        return $this->owner;
    }

    /**
     * @PhpUnitGen\set
     */
    public function setOwner(Employee $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @PhpUnitGen\get
     */
    public function getExpirationDate(): \DateTime
    {
        return $this->expirationDate;
    }

    /**
     * @PhpUnitGen\set
     */
    public function setExpirationDate(\DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }
}
