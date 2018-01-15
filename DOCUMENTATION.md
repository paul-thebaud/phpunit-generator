# PhpUnitGenerator: Documentation

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
$ php ./vendor/bin/phpunitgen generate [<config-path>]
```

The following command generate unit tests skeletons for a given configuration, but only for the given source file to the target file.

```bash
$ php ./vendor/bin/phpunitgen generate-one <source-file-path> <target-file-path> [<config-path>]
```

#### Console command using the default configuration

The two following command will use the default configuration file (available [here](config/default.phpunitgen.config.php)).

Because of you are not giving any configuration, you need to give a source directory path and a target directory path.

This one generate unit tests skeletons for a source directory to a target directory.

```bash
$ php ./vendor/bin/phpunitgen generate-default <source-path> <target-path>
```

This one generate unit tests skeletons for a source file to a target file.

```bash
$ php ./vendor/bin/phpunitgen generate-default-one <config-path> <source-file-path> <target-file-path>
```

### Website usage

PhpUnitGen is available online on an Heroku server :

[https://phpunitgen.herokuapp.com/](https://phpunitgen.herokuapp.com/)

### Php code usage

Here is an example of using PhpUnitGen in a php script.

```php
<?php

use \PhpUnitGen\Configuration\BaseConfig;
use \PhpUnitGen\Container\ContainerFactory;
use \PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;

// Require your composer autoload
require 'vendor/autoload.php';

// Create the config with default value (or a custom array)
$config = new BaseConfig();

// Create the dependency container
$container = (new ContainerFactory())->invoke($config);

$myTestClass = 'MyClassTest';
$myCode = "<?php class MyClass { ... some php code ... }";

// Execute PhpUnitGen on your code
$myUnitTestsSkeleton = $container->get(ExecutorInterface::class)->invoke($myCode, $myTestClass);
```

## Annotations

### Getter and setter

```php
<?php
/**
 * @PhpUnitGen\Getter
 */
public function getSomething(): int
{
    return $this->something;
}
```

```php
<?php
/**
 * @PhpUnitGen\Getter(myProperty)
 */
public function getSomething(): int
{
    return $this->myProperty;
}
```

```php
<?php
/**
 * @PhpUnitGen\Setter
 */
public function setSomething(int $something): void
{
    $this->something = $something;
}
```

```php
<?php
/**
 * @PhpUnitGen\Setter(myProperty)
 */
public function setSomething(int $something): void
{
    $this->myProperty = $something;
}
```

### Asserting something

```php
<?php
/**
 * @PhpUnitGen\AssertEquals(p=["1"], e="2")
 */
public function doSomething(int $something): int
{
    return $something + 1;
}
```

`p` is for `parameters`: an array of parameters.

`e` is for `expected`: an expected value.