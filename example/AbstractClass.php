<?php

namespace PHPUnitGenerator\Example;

use DateTime;
use HttpRequest;

abstract class AbstractClass
{
    public $property1;

    public function __construct(
        string $arg1,
        BaseClass $object,
        \ReflectionClass $reflection,
        DateTime $date,
        HttpRequest $request
    ) {
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
    public function setProperty1(bool $property1)
    {
        $this->property1 = $property1;
    }
}
