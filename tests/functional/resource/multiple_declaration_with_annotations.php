<?php

namespace My\SuperNamespace;

/**
 * @Pug\construct(["'John'"])
 */
class AFirstClass
{
    private $attr1, $name;
    private static $attr2;
    protected $attr3;
    public $attr4;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @Pug\assertSame("'John'")
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @Pug\Get()
     */
    public function getAttr1(): SomeClass
    {
        return $this->attr1;
    }

    /**
     * @Pug\Get()
     */
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
    /**
     * @Pug\assertEquals("'I do something!'")
     */
    public function doSomething()
    {
        return 'I do something!';
    }
}

trait AFirstTrait
{
    /**
     * @Pug\assertEquals("'I do something useful!'")
     */
    public function aUsefulMethod()
    {
        return 'I do something useful!';
    }
}