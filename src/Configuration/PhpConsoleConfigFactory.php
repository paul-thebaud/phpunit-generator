<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class PhpConsoleConfigFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpConsoleConfigFactory implements ConsoleConfigFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(string $configPath): ConsoleConfigInterface
    {
        $configArray = require $configPath;
        if (! is_array($configArray)) {
            throw new InvalidConfigException('Unable to parse PHP config');
        }

        return new ConsoleConfig($configArray);
    }
}
