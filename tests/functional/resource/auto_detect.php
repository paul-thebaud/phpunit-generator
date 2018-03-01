<?php

namespace My\SuperNamespace;

function doSomethingGlobal()
{
    echo 'Something global!';
}


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

    public function setAttr1(SomeClass $attr1)
    {
        $this->attr1 = $attr1;
    }

    public static function getAttr2(): SomeOtherClass
    {
        return self::$attr2;
    }

    public static function setAttr2(SomeOtherClass $attr2)
    {
        self::$attr2 = $attr2;
    }

    private function getAttr3(): \AnotherClass
    {
        return $this->attr3;
    }
}

interface MyInterface
{
    public function doSomething(): string;

    public function doSomethingAwesome(): bool;
}