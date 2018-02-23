#### PhpUnitGen - Annotations

#### Before using this package

__ALWAYS CHECK THE GENERATED UNIT TESTS SKELETONS!__

All generated unit tests must be checked completely before implementing them.

PHP files can have multiple patterns (containing namespaces, interfaces, class, traits, php functions ...).

If you find any issue, please report them [here](https://github.com/paul-thebaud/phpunit-generator/issues).


#### Annotations

Beyond using a configuration for your tests skeletons generation, PhpUnitGen provides
PHPDoc annotation that you can use in your files:

* Class instantiation information: `@PhpUnitGen\construct`.
* Automatic assertion for getter / setter methods: `@PhpUnitGen\get` or `@PhpUnitGen\set`.
* Method parameters: `@PhpUnitGen\params`.
* PHPUnit assertion on functions / methods results: `@PhpUnitGen\...` with a PHPUnit assertion (like `@PhpUnitGen\assertTrue`).
* Mock creation for methods call: `@PhpUnitGen\mock`.

Those annotations __MUST__ be written in a PHPDoc block.
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
function validateSomething(): bool { /* some PHP code */ }
```

You can find basic examples on using annotations [here](examples) with input class, and output tests class.

__Note__: PhpUnitGen annotations are __made to generate simple tests__.
If you want to test complex methods, you should write your assertions yourself.

#### Automatic generation

When using the `auto` parameter of configuration as `true`, PhpUnitGen will
detect the getter / setter methods and generate basic tests for them.

In PhpUnitGen, for the property `$name`, a getter method is called `getName`,
a setter method is called `setName`, and the class must have the property `$name`.

PhpUnitGen will test these methods with an auto-generated parameter
of the return type of the getter or of the argument type of the setter.

PhpUnitGen will also generate instantiation for class or trait if they have a `__construct` method.
It will use simple value to call the constructor method, so be careful with the generated instantiation.

#### Argument of annotations

Some annotations will need parameters. When generating tests skeletons, PhpUnitGen parse
these annotations like JSON content, so all parameters must be quoted with `"`. In
this parameter, you can write any PHP code, such as a mock creation: `"$this->createMock('MyClass')"`

__Do not forget__ to escape the backslash `\` or the `"` chars with a backslash `\`.

#### Class instantiation information

When PhpUnitGen generate a tests skeleton, it can not always detect the constructor, because it just parse one file.
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
    public function __construct(string $name, string $cellphone) { /* some PHP code */ }
}
```

If you want to build the class instance to test from another class
(when writing tests for an abstract class for example), you
can provide it to PhpUnitGen by adding the class absolute name:

```php
<?php
namespace Company;
/**
 * Construct the instance to tests by calling 'new \Company\Employee("John", "0123456789")'
 * @PhpUnitGen\construct("\Company\Employee", ["'John'", "'0123456789'"])
 */
abstract class AbstractPerson { /* some PHP code */ }
```

#### Getter and setter

To generate your tests for a getter or setter, PhpUnitGen provides
simple annotations.

```php
<?php
class Employee {
    private $name;
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
}
```

If you want to tell to PhpUnitGen which property is set or get by the method,
just add a string to describe the name of the property.

```php
<?php
class Employee {
    private $phone;
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
}
```

__Note__: `get` and `set` annotations support static and not static method in classes, traits or interfaces,
but do not support global functions (out of classes, traits or interfaces).
PhpUnitGen allow `get` and `set` methods to be private and protected.

#### Method result assertions

PhpUnitGen also provide annotations to generate simple:

```php
<?php
class Employee {
    private $phone;
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
}
```

An assertion annotation is composed of an optional string to describe 
the first parameter of assertion (generally it is the expected value):
* It could be any PHP expression.
* If not provided, PhpUnitGen will consider that assertion does not needs one, like `assertTrue`.
    
#### Method parameters

If the method you want to test needs parameters, you can use the `params` annotation.

```php
<?php
class Employee {
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
}
```

It works like the `construct` annotation, but parameters are not in a JSON array and, obviously, you
can not provide a class to instantiate.

#### Mocking objects

The mock annotation allows you to mock an object, which can be defined as a class property or a method test variable.

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
    public function __construct(Employee $employee) { /* some PHP code */ }
    
    /**
     * Create a mock only in this function.
     * @PhpUnitGen\mock("\\DateTime", "date")
     * 
     * Use it in test.
     * @PhpUnitGen\params("$date")
     * @PhpUnitGen\assertFalse()
     */
    public function isExpired(\DateTime $date): bool { /* some PHP code */ }
}
```
