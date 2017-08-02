# phpunit-generator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Total Downloads][ico-downloads]][link-downloads]

This package allows you to generate PHPUnit6 tests from PHP code or CLI without any autoload.
It can also follow some annotations you can write in your methods documentation.

## Structure

```
build/          ==> The build result (unit tests)
src/            ==> The package source files
template/       ==> The tests templates
test/           ==> The package unit tests
vendor/         ==> The dependencies
```

## Install

Via Composer

```bash
$ composer require paulthebaud/phpunit-generator
```

## Usage


```bash
$ php ./vendor/bin/phpunitgen <source_dir> <target_dir> [--option1 --option2]
```

Or maybe with a PHP code:

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

Please see [Documentation File](DOCUMENTATION.md) for details.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Paul Thébaud][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/paulthebaud/phpunit-generator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/paul-thebaud/phpunit-generator/master.svg?style=flat-square
[ico-coveralls]: https://img.shields.io/coveralls/paul-thebaud/phpunit-generator/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/paulthebaud/phpunit-generator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/paulthebaud/phpunit-generator
[link-travis]: https://travis-ci.org/paul-thebaud/phpunit-generator
[link-coveralls]: https://coveralls.io/github/paul-thebaud/phpunit-generator
[link-downloads]: https://packagist.org/packages/paulthebaud/phpunit-generator
[link-author]: https://github.com/paul-thebaud
[link-contributors]: ../../contributors