<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Util\Json;

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
     * {@inheritdoc}
     */
    protected function decode(string $configPath): array
    {
        try {
            $configArray = Json::decode(file_get_contents($configPath));
        } catch (JsonException $exception) {
            throw new InvalidConfigException('Unable to parse JSON config');
        }
        if (! is_array($configArray)) {
            throw new InvalidConfigException('Invalid JSON config');
        }
        return $configArray;
    }
}
