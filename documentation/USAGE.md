# PhpUnitGen - Usage

## Before using this package

__DO NOT USE THIS PACKAGE WITHOUT CHECKING GENERATED TESTS!__

All generated unit tests must be checked completely before implementing them.

PHP files can have multiple patterns (containing namespaces, interfaces, class, traits, php functions ...).

If you find any issue, please report them [here](https://github.com/paul-thebaud/phpunit-generator/issues).

## Usage

__Note__: All across your generated tests skeletons, you will find `@todo` PHP annotations to complete them.

### Console usage

When you install this package, you can use the command line to generate your unit tests skeleton.
Use this command in project root directory.

```bash
$ php ./vendor/bin/phpunitgen
```

For this command, you will need a configuration file written in `YAML`, `JSON` or `PHP`.

* `YAML` example is available [here](examples/phpunitgen.config.yml).
* `JSON` example is available [here](examples/phpunitgen.config.json).
* `PHP` example is available [here](examples/phpunitgen.config.php).

By default, PhpUnitGen search for a configuration file named `phpunitgen.yml` at the project root.

But if you want to use a custom configuration path, you can use an option:

```bash
$ php ./vendor/bin/phpunitgen --config=my/custom/config.yml

$ php ./vendor/bin/phpunitgen -c=my/custom/config.yml
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

* If you use the `default` option with the `config` option, configuration will
be ignored and default configuration will be needed.
* If you use the `default` option, and you don't provide the `dir` or the `file`
option, PhpUnitGen will consider that source and target paths are directories.
* As PhpUnitGen use the Symfony Console package, you can combine multiple option together:
`$ php ./vendor/bin/phpunitgen -fc my/custom/config.yml source/file.php target/file.php` will parse one
file with your custom configuration.

### Configuration

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
It will also parse sub-directories. *null* if you have no directory to parse.
* __files__ [*array*]: An array of `source: target` files. PhpUnitGen will parse each files in your source file (an array key)
and put the generated unit tests skeletons in your target file (an array value).
*null* if you have no file to parse.

### Website usage

PhpUnitGen is available online on an Heroku server:

[https://phpunitgen.herokuapp.com/](https://phpunitgen.io/)

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
