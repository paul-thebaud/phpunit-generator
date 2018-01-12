<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;
use Respect\Validation\Validator;

/**
 * Class JsonConsoleConfigFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class JsonConsoleConfigFactory extends AbstractConsoleConfigFactory
{
    /**
     * Decode a configuration file to get a configuration array.
     *
     * @param string $configPath The configuration file path.
     *
     * @return array The decoded configuration array.
     *
     * @throws InvalidConfigException If the configuration is invalid.
     */
    protected function decode(string $configPath): array
    {
        $configArray = json_decode(file_get_contents($configPath), true);
        if (! Validator::arrayType()->validate($configArray)) {
            throw new InvalidConfigException('Unable to parse JSON config');
        }
        return $configArray;
    }
}
