<?php

namespace Company\Entity;

/**
 * Class EmployeeCard.
 *
 * @PhpUnitGen\mock("Employee", "employeeMock")
 * @PhpUnitGen\mock("\\DateTime", "dateMock")
 *
 * @PhpUnitGen\constructor([
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
     * @PhpUnitGen\getter
     */
    public function getOwner(): Employee
    {
        return $this->owner;
    }

    /**
     * @PhpUnitGen\setter
     */
    public function setOwner(Employee $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @PhpUnitGen\getter
     */
    public function getExpirationDate(): \DateTime
    {
        return $this->expirationDate;
    }

    /**
     * @PhpUnitGen\setter
     */
    public function setExpirationDate(\DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }
}
