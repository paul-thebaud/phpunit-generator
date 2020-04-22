<img src="https://raw.github.com/paul-thebaud/phpunit-generator-assets/master/logos/logo.svg?sanitize=true" width="200px">

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg)](https://php.net/)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Scrutinizer][ico-scrutinizer]][link-scrutinizer]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## :warning: Repository and package abandonned :warning:

**This repository and the `paulthebaud/phpunit-generator` package are abandonned, in favor of the new version you can check out [here](https://phpunitgen.io). If you want to use as command line, use this package instead: [phpunitgen/console](https://github.com/paul-thebaud/phpunitgen-console).**

---

**Following remains for historical purpose.**

PhpUnitGen is an open source development tool which will help you writing your unit tests for PHPUnit 6 / 7:

* Generate unit skeleton for all PHP files including classes, traits, interfaces and global functions.
* Automatically generate a few simple unit tests (like getter / setter methods tests, class instantiation).

You can try and use this package on a web application, at [phpunitgen.io](https://phpunitgen.io).

__Version 2 of PhpUnitGen is now available, but be careful, it breaks the PhpUnitGen 1.x.x API.__

![Image of PhpUnitGen rendering](https://raw.github.com/paul-thebaud/phpunit-generator-assets/master/logos/example.png)

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

## Documentation

PhpUnitGen documentation is available online on [doc.phpunitgen.io](https://doc.phpunitgen.io).

It give multiple information on PhpUnitGen:
* [Installation of the package](https://doc.phpunitgen.io/en/installation.html).
* [Usage of web application](https://doc.phpunitgen.io/en/website.html).
* [Usage of command line](https://doc.phpunitgen.io/en/terminal.html).
* [Usage of with PHP code](https://doc.phpunitgen.io/en/php.html).
* [Usage of annotations](https://doc.phpunitgen.io/en/annotations.html).
* [Communication around the PhpUnitGen project](https://doc.phpunitgen.io/en/about.html).

## Running tests

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
