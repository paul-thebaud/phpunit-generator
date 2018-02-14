# PhpUnitGen: Documentation

This documentation contains console commands and annotations usage.

## Before using this package

__DO NOT USE THIS PACKAGE WITHOUT CHECKING GENERATED TESTS!__

All generated unit tests must be checked completely before implementing them.

PHP files can have multiple patterns (containing namespaces, interfaces, class, traits, php functions ...).

If you find any issue, please report them [here](https://github.com/paul-thebaud/phpunit-generator/issues).

## Usage

__Note__: All across your generated tests skeletons, you will find `@todo` PHP annotations to complete them.

### Console usage

When you install this package, you can use the command line to generate your unit tests skeleton.

```bash
$ php ./vendor/bin/phpunitgen
```

For this command, you will need a configuration file written in `YAML`, `JSON` or `PHP`.

* `YAML` example is available [here](examples/phpunitgen.config.yml).
* `JSON` example is available [here](examples/phpunitgen.config.json).
* `PHP` example is available [here](examples/phpunitgen.config.php).

By default, PhpUnitGen search for a configuration file named `./phpunitgen.yml`.

But if you want to use a custom configuration path, you can use an option:

```bash
$ php ./vendor/bin/phpunitgen --config=my/custom/config.json

$ php ./vendor/bin/phpunitgen -c=my/custom/config.json
```

Use PhpUnitGen on one file only (use of `file` option need a source and a target):

```bash
$ php ./vendor/bin/phpunitgen --file source/file.php target/file.php

$ php ./vendor/bin/phpunitgen -f source/file.php target/file.php
```

Use PhpUnitGen on one directory only (use of `dir` option need a source and a target):

```bash
$ php ./vendor/bin/phpunitgen --dir source/dir target/dir

$ php ./vendor/bin/phpunitgen -d source/dir target/dir
```

Use PhpUnitGen with default configuration (use of default configuration need a source and a target):

```bash
$ php ./vendor/bin/phpunitgen --default --file source/file.php target/file.php
$ php ./vendor/bin/phpunitgen --default --dir source/dir target/dir

$ php ./vendor/bin/phpunitgen -D -f source/file.php target/file.php
$ php ./vendor/bin/phpunitgen -D -d source/dir target/dir
```

__Note:__

* `dir` and `file` options can be combined with the `config` option.
* If you use the `default` option with the `config` option, configuration will
be ignored and default configuration will be needed.
* If you use the `default` option, and you don't provide the `dir` or the `file`
option, PhpUnitGen will consider that source and target paths are directories.

### Console configuration

A configuration file needs the following parameters :

* __overwrite__ [*boolean*]: Set *true* if you want to erase old files with the new ones.
* __backup__ [*boolean*]: Set *true* if you want to backup old files before erase them when `overwrite` is set to *true*.
Backup files while be named as following: `your_file.php.bak`
* __interface__ [*boolean*]: Set *true* if you want to generate unit tests skeletons for interface too.
* __private__ [*boolean*]: Set *true* if you want to generate unit tests skeletons for private / protected methods too.
* __auto__ [*boolean*]: Set *true* if you want to automatically generate `getter` / `setter` unit tests, and class or trait instantiation.
* __ignore__ [*boolean*]: Set *true* if you want to ignore errors that are not fatal.
* __exclude__ [*string* or *null*]: A PHP regex to filter files that have not to be parsed. Set as *null* if you do not want to use an exclude regex.
* __include__ [*string* or *null*]: A PHP regex to filter files that have to be parsed. Set as *null* if you do not want to use an include regex.
* __dirs__ [*array*]: An array of `source: target` directories. PhpUnitGen will parse each files in your source directory (an array key)
and put the generated unit tests skeletons in your target directory (an array value).
It will also parse sub-directories.
* __files__ [*array*]: An array of `source: target` files. PhpUnitGen will parse each files in your source file (an array key)
and put the generated unit tests skeletons in your target file (an array value).

### Website usage

PhpUnitGen is available online on an Heroku server:

[https://phpunitgen.herokuapp.com/](https://phpunitgen.herokuapp.com/)

### PHP code usage

Here is an example of using PhpUnitGen in a PHP script.

```php
<?php

use PhpUnitGen\Configuration\BaseConfig;
use PhpUnitGen\Container\ContainerFactory;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;

// Require your composer autoload
require 'vendor/autoload.php';

// Create the config with default value (or a custom array)
$config = new BaseConfig();

// Create the dependency container
$container = (new ContainerFactory())->invoke($config);

$myTestClass = 'MyClassTest';
$myCode = "<?php class MyClass { /* ... some PHP code ... */ }";

// Execute PhpUnitGen on your code to get the tests file content
$myUnitTestsSkeleton = $container->get(ExecutorInterface::class)->invoke($myCode, $myTestClass);
```

## Annotations

Beyond using a configuration for your tests skeletons generation, PhpUnitGen provides
PHPDoc annotation that you can use in your files:

* Class instantiation information: `@PhpUnitGen\construct`.
* Automatic assertion for getter / setter methods: `@PhpUnitGen\get` or `@PhpUnitGen\set`.
* Method parameters: `@PhpUnitGen\params`.
* PHPUnit assertion on functions / methods results: `@PhpUnitGen\...` with a PHPUnit assertion (like `@PhpUnitGen\assertTrue`).
* Mock creation for methods call: `@PhpUnitGen\mock`.

These annotations __MUST__ be written in a PHPDoc block.
They all start with `@PhpUnitGen` or `@Pug`
(which is not case sensitive, so you can write `@phpunitgen` for example).

```php
<?php
/**
 * Working!
 * @PhpUnitGen\assertTrue()
 * @Pug\assertTrue()
 * @pug\assertTrue()
 * @phpUnitGen\assertTrue()
 * 
 * Not working:
 * @PhpUnitGenerator\assertTrue()
 */
function doSomething() {}
```

You can find basic examples on using annotations [here](examples) with input class, and output tests class.

__Note__: PhpUnitGen annotations are __made to generate simple tests__.
If you want to test complex methods, you should write your assertions yourself.

### Automatic generation

When using the `auto` parameter of configuration as `true`, PhpUnitGen will
detect the getter / setter methods and generate basic tests for them.

In PhpUnitGen, for the property `$name`, a getter method is called `getName`,
a setter method is called `setName`, and the class must have the property `$name`.

PhpUnitGen will test these methods with an auto-generated parameter
of the return type of the getter or of the argument type of the setter.

PhpUnitGen will also generate instantiation for class or trait if they have a `__construct` method.
It will use simple value to call the constructor method, so be careful with the generated instantiation.

### Argument of annotations

Some annotations will need parameters. When generating tests skeletons, PhpUnitGen parse
these annotations like JSON content, so all string must be quoted with `"`.

So __do not forget__ to escape the backslash `\` or the `"` chars with a backslash `\`.

### Class instantiation information

When PhpUnitGen generate a tests skeleton, it can not instantiate the class
because it does not know which parameters you want to use.
If you provide this annotation on your class documentation, it will instantiate the class with your parameters:

```php
<?php
namespace Company;
/**
 * Construct the instance to tests by calling 'new Employee("John", "0123456789")'
 * @PhpUnitGen\construct(["'John'", "'012-345-6789'"])
 */
class Employee extends AbstractPerson
{
    public function __construct(string $name, string $cellphone) {}
}
```

If you want to use a to build the class instance to test from another class
(when writing tests for an abstract class for example), you
can provide it to PhpUnitGen by adding the class absolute name:

```php
<?php
namespace Company;
/**
 * Construct the instance to tests by calling 'new \Company\Employee("John", "0123456789")'
 * @PhpUnitGen\construct("\Company\Employee", ["'John'", "'0123456789'"])
 */
abstract class AbstractPerson {}
```

### Getter and setter

To generate your tests for a getter or setter, PhpUnitGen provides
simple annotations.

```php
<?php
/**
 * Assert that when calling this method the property $name is get.
 * @PhpUnitGen\get
 */
public function getName(): string
{
    return $this->name;
}
/**
 * Assert that when calling this method the property $name is set.
 * @PhpUnitGen\set
 */
public function setName(string $name): void
{
    $this->name = $name;
}
```

If you want to tell to PhpUnitGen which property is set or get by the method,
just add a string to describe the name of the property.

```php
<?php
/**
 * Assert that when calling this method the property $phone is get.
 * @PhpUnitGen\get("phone")
 */
public function getCellphone(): string
{
    return $this->phone;
}
/**
 * Assert that when calling this method the property $phone is set.
 * @PhpUnitGen\set("phone")
 */
public function setCellphone(string $phone): void
{
    $this->phone = $phone;
}
```

__Note__: `get` and `set` annotations support static and not static method in classes, traits or interfaces,
but do not support global functions (out of classes, traits or interfaces).
Also, for PhpUnitGen, a `getter` or a `setter` is a public method.

### Method result assertions

PhpUnitGen also provide annotations to generate simple:

```php
<?php
/**
 * Assert method call will return a not null result.
 * @PhpUnitGen\assertNotNull()
 * Assert method call will return '012-345-6789'.
 * @PhpUnitGen\assertEquals("'0123456789'")
 */
public function getCellphone(): string
{
    return $this->phone;
}
```

An assertion annotation is composed of the following parameters:

* (optional) A string to describe the first parameter of assertion (generally it is the expected value):
    * It could be any PHP expression.
    * If not provided, PhpUnitGen will consider that assertion does not needs one, like `assertTrue`.
    
### Method parameters

If the method you want to test needs parameters, you can use the `params` annotation.

```php
<?php
/**
 * Assert that when calling this method the property $phone is set.
 * @PhpUnitGen\params("'John'", "'0123456789'")
 * @PhpUnitGen\assertNotNull()
 * @PhpUnitGen\assertEquals("'John: 0123456789'")
 */
public static function getEmployeeInfo(string $name, string $phone): string
{
    return $name . ': ' . $phone;
}
```

### Mocking objects

```php
<?php
namespace Company;
/**
 * Create a mock of '\Company\Employee' and add it to class properties as '$employee'.
 * @PhpUnitGen\mock("\\Company\\Employee", "employee")
 * 
 * Use the class property "$employee" with $this in the constructor.
 * @PhpUnitGen\construct(["$this->employee"])
 */
class EmployeeCard {
    public function __construct(Employee $employee) {}
    
    /**
     * Create a mock only in this function.
     * @PhpUnitGen\mock("\\DateTime", "date")
     * 
     * Use it in test.
     * @PhpUnitGen\params("$date")
     * @PhpUnitGen\assertFalse()
     */
    public function isExpired(\DateTime $date): bool {}
}
```