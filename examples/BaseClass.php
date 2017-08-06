<?php

namespace PHPUnitGenerator\Example;

class BaseClass
{
    public $property1;

    protected $property2;

    public function __construct()
    {
        // Some PHP code
    }

    /**
     * @PHPUnitGen\AssertEquals(5:{2, 3})
     * @PHPUnitGen\AssertEquals(0)
     */
    public static function simpleAddition(int $a = 0, int $b = 0): int
    {
        return $a + $b;
    }

    /**
     * @PHPUnitGen\Set()
     */
    public function setProperty1($property1)
    {
        $this->property1 = $property1;
    }

    /**
     * @PHPUnitGen\Get()
     */
    protected function getProperty2()
    {
        return $this->property2;
    }
}
