<img src="https://raw.github.com/paul-thebaud/phpunit-generator-assets/master/logos/logo.svg?sanitize=true" width="200px">

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Scrutinizer][ico-scrutinizer]][link-scrutinizer]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

PhpUnitGen is an open source development tool which will help you writing your unit tests for PHPUnit 6 / 7:
* Generate unit skeleton for all PHP files.
* Automatically generate a few simple unit tests (like getter / setter methods tests, class instantiation).

## Package structure

```
build/      ==> Build results (code coverage ...) [only after running composer test].
config/     ==> Default PhpUnitGen configurations.
examples/   ==> Examples about this package (configuration, parsing examples).
src/        ==> Package source files.
template/   ==> Package template for generated tests skeletons.
tests/      ==> Package unit tests.
vendor/     ==> Composer dependencies [only after running composer install].
```

## Installation

Best way to install this package is with composer dependency manager.

```bash
$ composer require --dev paulthebaud/phpunit-generator ^2.0
```

`--dev` option is used to install this package only in development environment.

## Basic usage

A detailed documentation is available [here](DOCUMENTATION.md), but here is a simple description of usages.

PhpUnitGen basic usage is from command line with the following command.

```bash
$ php ./vendor/bin/phpunitgen
```

__Note__: All across your generated tests skeletons, you will find `@todo` PHP annotations to complete them.

For this command, you will need a configuration file written in `Yaml`, `Json` or `Php`.

* `Yaml` example is available [here](examples/phpunitgen.config.yml).
* `Json` example is available [here](examples/phpunitgen.config.json).
* `Php` example is available [here](examples/phpunitgen.config.php).

By default, PhpUnitGen search for a configuration file named `./phpunitgen.yml`.

But if you want to use a custom configuration path, you can use an option:

```bash
$ php ./vendor/bin/phpunitgen --config=my/custom/config.json
```

Use PhpUnitGen on one file only:

```bash
$ php ./vendor/bin/phpunitgen --file source/file.php target/file.php
```

Use PhpUnitGen on one directory only:

```bash
$ php ./vendor/bin/phpunitgen --dir source/dir target/dir
```

Use PhpUnitGen with default configuration:

```bash
$ php ./vendor/bin/phpunitgen --default --file source/file.php target/file.php
$ php ./vendor/bin/phpunitgen --default --dir source/dir target/dir
```

PhpUnitGen can also be used online on [this website](https://phpunitgen.heroku.com)

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for more details.

## Credits

- [Paul Thébaud][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/paulthebaud/phpunit-generator.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/paul-thebaud/phpunit-generator/master.svg
[ico-coveralls]: https://img.shields.io/coveralls/paul-thebaud/phpunit-generator/master.svg
[ico-scrutinizer]: https://scrutinizer-ci.com/g/paul-thebaud/phpunit-generator/badges/quality-score.png?b=master
[ico-downloads]: https://img.shields.io/packagist/dt/paulthebaud/phpunit-generator.svg

[link-packagist]: https://packagist.org/packages/paulthebaud/phpunit-generator
[link-travis]: https://travis-ci.org/paul-thebaud/phpunit-generator
[link-coveralls]: https://coveralls.io/github/paul-thebaud/phpunit-generator
[link-scrutinizer]: https://scrutinizer-ci.com/g/paul-thebaud/phpunit-generator/
[link-downloads]: https://packagist.org/packages/paulthebaud/phpunit-generator
[link-author]: https://github.com/paul-thebaud
[link-contributors]: ../../contributors