# PhpUnitGenerator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Scrutinizer][ico-scrutinizer]][link-scrutinizer]

[![Total Downloads][ico-downloads]][link-downloads]

This package will help you writing your unit tests:
* Generate unit skeleton for all php files.
* Automatically generate a few simple unit tests (like getter / setter methods tests).

## Package structure

```
config/         ==> Default PhpUnitGen configurations.
examples/       ==> Examples about this package (configuration, parsing examples).
src/            ==> Package source files
template/       ==> Tests templates
test/           ==> Package unit tests
vendor/         ==> Composer dependencies
```

## Installation

Best way to install this package is with composer dependency manager.

```bash
$ composer require --dev paulthebaud/phpunit-generator
```

`--dev` option is used to install this package only in development environment.

## Basic usage

PhpUnitGenerator basic usage is from command line with the following command.

```bash
$ php ./vendor/bin/phpunitgen generate <config-path>
```
For this command, you will need a configuration file written in `Yaml`, `Json` or `Php`.

* `Yaml` example is available [here](examples/phpunitgen.config.yml).
* `Json` example is available [here](examples/phpunitgen.config.json).
* `Php` example is available [here](examples/phpunitgen.config.php).

3 other commands are available:

```bash
$ php ./vendor/bin/phpunitgen generate-one <config-path> <source-file-path> <target-file-path>

$ php ./vendor/bin/phpunitgen generate-default <source-path> <target-path>

$ php ./vendor/bin/phpunitgen generate-default-one <config-path> <source-file-path> <target-file-path>
```

* __generate-one__: To generate unit tests skeleton for only one file.
* __generate-default__: To generate unit tests skeletons of a repository with the default configuration.
* __generate-default-one__: To generate unit tests skeleton for only one file with the default configuration.

Check them on the [Usage section](DOCUMENTATION.md#Usage) of documentation for more details.

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