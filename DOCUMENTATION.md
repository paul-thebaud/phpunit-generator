# PhpUnitGen: Documentation

This documentation contains console command and annotations usage.

## Before using this package

__DO NOT USE THIS PACKAGE WITHOUT CHECKING GENERATED TESTS!__

All generated unit tests must be checked completely before implementing them.

Php files can have multiple patterns (containing namespaces, interfaces, class, traits, php functions ...).

If you find any issue, please report them [here](https://github.com/paul-thebaud/phpunit-generator/issues).

## Usage

### Console usage

When you install this package, you can use the command line to generate your unit tests skeleton.

#### Console command using a custom configuration

The two following commands will need a configuration file that need to be a `.yml`, `.json` or `.php` file.

A configuration file needs the following parameters :

* __overwrite__ [*boolean*]: Set *true* if you want to erase old files with the new ones.
* __interface__ [*boolean*]: Set *true* if you want to generate unit tests skeletons for interface too.
* __auto__ [*boolean*]: Set *true* if you want to automatically generate `getter` / `setter` unit tests.
* __ignore__ [*boolean*]: Set *true* if you want to ignore errors that are not fatal.
* __exclude__ [*string*]: A php regex to filter files that have not to be parsed. Set as *null* if you do not want to use an exclude regex.
* __include__ [*string*]: A php regex to filter files that have to be parsed. Set as *null* if you do not want to use an include regex.
* __dirs__ [*array*]: An array of `source: target` directories. PhpUnitGen will parse each files in your source directory (an array key)
and put the generated unit tests skeletons in your target directory (an array value).
* __files__ [*array*]: An array of `source: target` files. PhpUnitGen will parse each files in your source file (an array key)
and put the generated unit tests skeletons in your target file (an array value).

To help you configuring PhpUnitGen, you can use the following configuration file examples, written in `Yaml`, `Json` and `Php`:

* `Yaml` example is available [here](examples/phpunitgen.config.yml).
* `Json` example is available [here](examples/phpunitgen.config.json).
* `Php` example is available [here](examples/phpunitgen.config.php).

Notice that if don't give a `config-path`, it will use the default path: `./phpunitgen.yml`.

The following command generate unit tests skeletons for a given configuration.

```bash
$ php ./vendor/bin/phpunitgen gen [<config-path>]
```

The following command generate unit tests skeletons for a given configuration, but only for the given source file to the target file.

```bash
$ php ./vendor/bin/phpunitgen gen-one <source-file-path> <target-file-path> [<config-path>]
```

#### Console command using the default configuration

The two following command will use the default configuration file (available [here](config/default.phpunitgen.config.php)).

Because of you are not giving any configuration, you need to give a source directory path and a target directory path.

This one generate unit tests skeletons for a source directory to a target directory.

```bash
$ php ./vendor/bin/phpunitgen gen-def <source-path> <target-path>
```

This one generate unit tests skeletons for a source file to a target file.

```bash
$ php ./vendor/bin/phpunitgen gen-one-def <source-file-path> <target-file-path>
```

### Website usage

PhpUnitGen is available online on an Heroku server:

[https://phpunitgen.herokuapp.com/](https://phpunitgen.herokuapp.com/)

### Php code usage

Here is an example of using PhpUnitGen in a php script.

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
$myCode = "<?php class MyClass { /* ... some php code ... */ }";

// Execute PhpUnitGen on your code to get the tests file content
$myUnitTestsSkeleton = $container->get(ExecutorInterface::class)->invoke($myCode, $myTestClass);
```

## Annotations

Beyond using a configuration for your tests skeletons generation, PhpUnitGen provides
Phpdoc annotation that you can use in your files:

* Class instantiation information.
* Automatic assertion for getter / setter methods.
* PHPUnit assertion on functions / methods results.
* Mock creation for methods call.

These annotations __MUST__ be written in a Phpdoc block.
They all start with `@PhpUnitGen` or `@Pug`
(which is not case sensitive, so you can write `@phpunitgen` for example).

```php
<?php
/**
 * Following works!
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

### Automatic generation

When using the `auto` parameter of configuration as `true`, PhpUnitGen will
detect the getter / setter methods and generate basic tests for them.

In PhpUnitGen, for the property `$name`, a getter method is called `getName`,
a setter method is called `setName`, and the class must have the property `$name`.

PhpUnitGen will test these methods with an auto-generated parameter
of the return type of the getter or of the argument type of the setter.

### Arguments for annotations

Each annotation that needs the method parameters, or the expected value,
needs a string that represents the PHP code to use.

Example: You want to use the addition of variables `a` and `b`,
you will wrote `$a + $b`.

### Class instantiation information

When PhpUnitGen generate a tests skeleton, it can not instantiate the class
because it does not know which parameters you want to use.
If you provide this annotation on your class documentation, it will instantiate the class with your parameters:

```php
<?php
namespace Company;
/**
 * Construct the instance to tests by calling 'new Employee("John", "012-345-6789")'
 * @PhpUnitGen\constructor(['"John"', '"012-345-6789"'])
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
 * Construct the instance to tests by calling 'new \Company\Employee("John", "012-345-6789")'
 * @PhpUnitGen\constructor('\Company\Employee', ['"John"', '"012-345-6789"'])
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
 * @PhpUnitGen\getter
 */
public function getName(): string
{
    return $this->name;
}
/**
 * Assert that when calling this method the property $name is set.
 * @PhpUnitGen\setter
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
 * @PhpUnitGen\getter('phone')
 */
public function getCellphone(): string
{
    return $this->phone;
}
/**
 * Assert that when calling this method the property $phone is set.
 * @PhpUnitGen\setter('phone')
 */
public function setCellphone(string $phone): void
{
    $this->phone = $phone;
}
```

### Asserting something

PhpUnitGen also provide annotations to generate simple:

```php
<?php
class Calcul
{
    /**
     * Assert first method call with '2' and '3' equals '5'.
     * @PhpUnitGen\assertEquals(['2', '3'], '5')
     * 
     * Assert second method call with no parameters is not null and equals '2'.
     * @PhpUnitGen\assertNotNull(2)
     * @PhpUnitGen\assertEquals(2, '0')
     * 
     * Assert third method call with '5' and '5' equals '10'.
     * @PhpUnitGen\assertEquals(3, ['5', '5'], '10')
     */
    public static function add(int $a = 0, int $b = 0): int
    {
        return $a + $b;
    }
}

// Generated method tests
public function testAdd()
{
    $result1 = Calcul::add(2, 3);
    $this->assertEquals(5, $result1);
    $result2 = Calcul::add();
    $this->assertNotNull($result2);
    $this->assertEquals(0, $result2);
    $result3 = Calcul::add(5, 5);
    $this->assertEquals(10, $result3);
}
```

An assertion annotation is composed of the following parameters:

* (optional) A integer to describe the number of method call:
    * If it is not set, PhpUnitGen will use the first method call result.
    * If it is set with an integer `n`, PhpUnitGen will use the `n` method call result.
* (optional) An array giving method parameters to use:
    * It is optional for methods does not have parameters or methods with default parameter values.
* (optional) A string to describe the expected value:
    * It could be any PHP expression.

### Mocking object for method or constructor parameters

```php
<?php
namespace Company;
/**
 * Create a mock of '\Company\Employee' and add it to class properties as '$employee'.
 * @PhpUnitGen\mock('\Company\Employee', 'employee')
 * 
 * Use the class property '$employee' with $this in the constructor.
 * @PhpUnitGen\constructor(['$this->employee'])
 */
class EmployeeCard {
    public function __construct(Employee $employee) {}
    
    /**
     * Create a mock only in this function.
     * @PhpUnitGen\mock('\DateTime', 'date')
     * 
     * Use it in test.
     * @PhpUnitGen\assertFalse('$date')
     */
    public function isExpired(\DateTime $date): bool {}
}
```