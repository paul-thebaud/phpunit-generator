<?php

namespace My\SuperNamespace;

class AFirstClass
{
    private $attr1;
    private static $attr2;
    protected $attr3;
    public $attr4;

    public function getAttr1(): SomeClass
    {
        return $this->attr1;
    }

    public static function getAttr2(): SomeOtherClass
    {
        return self::$attr2;
    }

    private function getAttr3(): \AnotherClass
    {
        return $this->attr3;
    }
}

interface AFirstInterface
{
    public function doSomething();
}

abstract class ASecondClass implements AFirstInterface
{
    public function doSomething()
    {
        echo 'I do something!';
    }
}

trait AFirstTrait
{
    public function aUsefulMethod()
    {
        echo 'I do something useful!';
    }
}