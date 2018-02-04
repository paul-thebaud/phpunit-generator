# PhpUnitGenerator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Scrutinizer][ico-scrutinizer]][link-scrutinizer]

[![Total Downloads][ico-downloads]][link-downloads]

PhpUnitGen is a development tool which will help you writing your unit tests for PHPUnit 6 / 7:
* Generate unit skeleton for all php files.
* Automatically generate a few simple unit tests (like getter / setter methods tests).

## Package structure

```
build/      ==> Build results (code coverage ...) [only after running composer test].
config/     ==> Default PhpUnitGen configurations.
examples/   ==> Examples about this package (configuration, parsing examples).
src/        ==> Package source files.
tests/      ==> Package unit tests.
vendor/     ==> Composer dependencies [only after running composer install].
```

## Installation

Best way to install this package is with composer dependency manager.

```bash
$ composer require --dev paulthebaud/phpunit-generator ~2.0
```

`--dev` option is used to install this package only in development environment.

## Basic usage

A detailed documentation is available [here](DOCUMENTATION.md), but here is a simple description of usages.

PhpUnitGenerator basic usage is from command line with the following command.

```bash
$ php ./vendor/bin/phpunitgen gen [<config-path>]
```
For this command, you will need a configuration file written in `Yaml`, `Json` or `Php`.

* `Yaml` example is available [here](examples/phpunitgen.config.yml).
* `Json` example is available [here](examples/phpunitgen.config.json).
* `Php` example is available [here](examples/phpunitgen.config.php).

Notice that if don't give a `config-path`, it will use the default path: `./phpunitgen.yml`.

3 other commands are available:

```bash
$ php ./vendor/bin/phpunitgen gen-one <source-file-path> <target-file-path> [<config-path>]

$ php ./vendor/bin/phpunitgen gen-def <source-path> <target-path>

$ php ./vendor/bin/phpunitgen gen-one-def <source-file-path> <target-file-path>
```

* __gen-one__: To generate unit tests skeleton for only one file.
* __gen-def__: To generate unit tests skeletons of a repository with the default configuration.
* __gen-one-def__: To generate unit tests skeleton for only one file with the default configuration.

PhpUnitGenerator can also be used online on [this website](https://phpunitgen.heroku.com)

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