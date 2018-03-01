<?php

namespace AnotherNamespace\Current;

use E;
use MyNamespace\A;
use MyNamespace\AnotherNamespace\{
    C, D
};
use MyNamespace\B as Another;
use MyNamespace\F;

/**
 * Some documentation...
 */
function globalFunc1($argWithoutAnything, int $argInt, ?D $argClassD): ?Another
{
}

function globalFunc2($argDefaultValue = 'default', \DateTime $argFullyQualified, A $argClassA): E
{
}

function globalFunc3(Current\G $argQualified, MyOtherClass $argUnqualified, A\MySub $argSubClassA): int
{
}

function globalFunc4(H\SomeClass $argH, F $argF, ... $variadic)
{
}

/**
 * Another doc for Class.
 */
final class AFinalClassWithGetter
{
    private static $foo;
    private $bar;

    public static function getFoo()
    {
    }

    public static function setFoo($foo)
    {
    }

    public function getBar()
    {
    }

    public function setBar($bar)
    {
    }

    public function getBaz($baz)
    {
    }

    public function setBaz($baz)
    {
    }
}

class AClass
{
    public $withDefaultProperty = 'default value';
    static $staticProperty;
    const CONST_PROPERTY = 'value';

    /**
     * Another doc for class method.
     */
    static function staticFunc()
    {
    }

    final function finalFunc()
    {
    }
}

abstract class AAbstractClass
{
    abstract function abstractFunction($arg1, $arg2, $arg3);
}

/**
 * Another doc for Trait.
 */
trait ATrait
{
    public $publicProperty;
    protected $protectedProperty;
    private $privateProperty;

    function funcWithoutVisibility()
    {
    }

    public function publicFunc()
    {
    }

    protected function protectedFunc()
    {
    }

    private function privateFunc()
    {
    }
}

/**
 * Another doc for Interface.
 */
interface AInterface
{
    public function interfaceFunc();
}

