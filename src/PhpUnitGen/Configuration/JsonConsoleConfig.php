<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class JsonConsoleConfig.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class JsonConsoleConfig extends ConsoleConfig
{
    /**
     * {@inheritdoc}
     */
    public function __construct($config)
    {
        $config = json_decode($config, true);

        if (! is_array($config)) {
            throw new InvalidConfigException('Unable to parse JSON config');
        }

        parent::__construct($config);
    }
}
