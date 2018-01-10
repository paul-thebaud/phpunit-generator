<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConsoleConfig.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class YamlConsoleConfig extends ConsoleConfig
{
    /**
     * {@inheritdoc}
     */
    public function __construct($config)
    {
        try {
            $config = Yaml::parse($config);
        } catch (ParseException $exception) {
            throw new InvalidConfigException(sprintf(
                'Unable to parse YAML config: %s',
                $exception->getMessage()
            ));
        }

        parent::__construct($config);
    }
}