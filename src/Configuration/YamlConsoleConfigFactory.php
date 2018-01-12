<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConsoleConfigFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class YamlConsoleConfigFactory implements ConsoleConfigFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(string $configPath): ConsoleConfigInterface
    {
        try {
            $configArray = Yaml::parse(file_get_contents($configPath));
        } catch (ParseException $exception) {
            throw new InvalidConfigException(sprintf(
                'Unable to parse YAML config: %s',
                $exception->getMessage()
            ));
        }

        return new ConsoleConfig($configArray);
    }
}
