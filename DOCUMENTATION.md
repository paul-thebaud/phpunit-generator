# phpunit-generator

## Documentation

Basic usage of this package is to generate your tests skeleton (and maybe some basic tests).

### Before generating your tests

__DO NOT USE THIS PACKAGE WITHOUT CHECKING GENERATED TESTS!__

You must check and complete all tests you generate, including the most basic methods.

Files to parse must declare __ONE__ class, abstract class, trait or interface to be accept.

### Basic usage

You can go to [Example folder](example/) to see concrete examples.

#### CLI (Command Line Interface)

With this package, you can generate a file unit tests with the following command:

``` bash
$ php ./vendor/bin/phpunitgen <source_dir> <target_dir> [--option1 --option2]
```

__Source file__ will be the file we generate tests for, and __target file__ will be the file to put test in.

There is a few available options:
* __file__ `--file`: If the source and the target are files instead of directories.
* __overwrite__ `--overwrite`: Add this option will erase old target files with new one. Be careful using it.
* __interface__ `--interface`: Add this option if you want to generate tests for Interface too.
* __auto__ `--auto`: Add this option if you want to automaticaly generate tests for getter / setter method.
* __ignore__ `--ignore`: Add this option if you want to ignore errors that can be ignored.
* __no-color__ `--no-color`: To display all CLI message without colors.
* __include__ `--include=<regex>`: A PHP regex (/.*.php/ for example) that files should match to have a tests generation.
* __exclude__ `--exclude=<regex>`: A PHP regex (/.*config.php/ for example) that files must not match to have a tests generation.

#### Custom PHP Code

```php
<?php

$testGenerator = new \PHPUnitGenerator\Generator\TestGenerator([
    // Options ...
]);

try {
    // This will echo the tests skeleton for "A_PHP_Class" class
    echo $testGenerator->generate(file_get_contents('A_PHP_Class.php'));
} catch (\PHPUnitGenerator\Exception\ExceptionInterface\ExceptionInterface $e) {
    // Errors ...
}
```

In PHP code usage, you can also add options when constructing you TestGenerator instance:

* __auto__: Add this option if you want to automaticaly generate tests for getter / setter method.
* __template-folder__: The template folder for Twig renderer (it will search "class.twig" in it).
* __debug__: If you want to allow debug on Twig renderer.

### Documentation parsing and annotations

In PHPUnit Generator, you can add annotation(s) to your method PHP
documentation to auto-generate unit tests in the test method.

There are 3 possibles annotations :

#### Basic annotation

```php
/** @PHPUnitGen\<phpunit_assertion_method>(<expectation>:{<parameters...>}) */
```

This annotation use some parameters:

* __phpunit_assertion_method__: It is the PHPUnit assertion method
you want ot use (like _assertEquals_, _assertInstanceOf_, _assertTrue_ ...).

* __expectation__: The method return expected value. Some PHPUnit methods
does not need it (like _assertTrue_), so in those cases, it can be null.

* __parameters__: The method parameters.

See this example, we want to auto generate some simple test for this method:

```php
<?php
// The class to test content

/** @PHPUnitGen\AssertFalse('expected string':{1, 2, 'a string'}) */
/** @PHPUnitGen\AssertTrue(:{4, 5, 'another'}) */
/** @PHPUnitGen\AssertNull() */
public function method(int $arg1 = 0, int $arg2 = 0, string $arg3 = 'default') {}
```

Those annotations will create basic PHPUnit tests:

```php
<?php
// The generated test for "method" in tests class

$this->AssertFalse('expectation string', $this->method(1, 2, 'a string'));
$this->AssertTrue($this->method(4, 5, 'another'));
$this->AssertNull($this->method());
```

#### Getter and setter annotation

```php
<?php
// The class to test content

/** @PHPUnitGen\Get() */
/** @PHPUnitGen\Set() */
```

Those two annotations will allow you to auto-generate tests for simple getter / setter.
You getter / setter needs to be named like the following:

```
get<MyProperty>() {}
set<MyProperty>() {}
```

#### Private or protected method

If the method to test is private or protected, PHPUnit Generator will access the method with PHP ReflectionClass.