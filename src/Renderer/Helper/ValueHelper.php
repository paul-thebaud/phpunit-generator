<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Renderer\Helper;

use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\PropertyInterface\TypeInterface;

/**
 * Class ValueHelper.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ValueHelper
{
    /**
     * Generate a PHP value as a string for the given type (bool, int ...).
     *
     * @param int|null    $type       The type to generate value for (from TypeInterface::*).
     * @param string|null $customType The custom type as a string if exists.
     *
     * @return string The generated PHP value string.
     *
     * @throws Exception If a custom type does not have a custom string type (to create the mock).
     */
    public function invoke(?int $type = null, ?string $customType = null): string
    {
        switch (true) {
            case TypeInterface::CUSTOM === $type:
                if ($customType === null) {
                    throw new Exception('Custom type must have a custom class to mock');
                }
                return sprintf('$this->createMock(%s::class)', $customType);
            case TypeInterface::OBJECT === $type:
                return '$this->createMock(\\DateTime::class)';
            case TypeInterface::BOOL === $type:
                return 'true';
            case TypeInterface::INT === $type:
                return '42';
            case TypeInterface::FLOAT === $type:
                return '42.42';
            case TypeInterface::ARRAY === $type:
            case TypeInterface::ITERABLE === $type:
                return '["a", "strings", "array"]';
            case TypeInterface::CALLABLE === $type:
                return 'function(): void {/* A callable */}';
            case TypeInterface::STRING === $type:
            case TypeInterface::MIXED === $type:
                return '"a string to test"';
            default:
                return '/** @todo Insert a value with a correct type here */';
        }
    }
}
