<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigFactoryInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\InvalidConfigException;
use Respect\Validation\Validator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class GenerateCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GenerateCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("gen")
            ->setDescription("Generate unit tests skeletons with a custom configuration")
            ->setHelp("Use it to generate your unit tests skeletons from a configuration file")
            ->addArgument('config-path', InputArgument::OPTIONAL, 'The configuration file path.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(InputInterface $input): ConsoleConfigInterface
    {
        $configPath = 'phpunitgen.yml';
        if ($input->hasArgument('config-path')) {
            $configPath = $input->getArgument('config-path');
        }

        if (! file_exists($configPath)) {
            throw new InvalidConfigException(sprintf('Config file "%s" does not exists.', $configPath));
        }

        $extension = pathinfo($configPath, PATHINFO_EXTENSION);
        if (! Validator::key($extension)->validate(static::CONSOLE_CONFIG_FACTORIES)) {
            throw new InvalidConfigException(
                sprintf('Config file "%s" must have .yml, .json or .php extension.', $configPath)
            );
        }

        /** @var ConsoleConfigFactoryInterface $factory */
        $factoryClass = static::CONSOLE_CONFIG_FACTORIES[$extension];
        $factory      = new $factoryClass();
        return $factory->invoke($configPath);
    }
}
