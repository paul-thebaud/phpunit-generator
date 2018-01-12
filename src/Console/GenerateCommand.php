<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\ConsoleConfigFactoryInterface;
use PhpUnitGen\Configuration\ConsoleConfigInterface;
use PhpUnitGen\Configuration\JsonConsoleConfigFactory;
use PhpUnitGen\Configuration\PhpConsoleConfigFactory;
use PhpUnitGen\Configuration\YamlConsoleConfigFactory;
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
     * @var string[] CONSOLE_CONFIG_FACTORIES Mapping array between file extension and configuration factories.
     */
    const CONSOLE_CONFIG_FACTORIES = [
        'yml'  => YamlConsoleConfigFactory::class,
        'json' => JsonConsoleConfigFactory::class,
        'php'  => PhpConsoleConfigFactory::class
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("generate")
            ->setDescription("Generate unit tests skeletons")
            ->setHelp("Use it to generate your unit tests skeletons from a config file")
            ->addArgument('config-path', InputArgument::REQUIRED, 'The config file path.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(InputInterface $input): ConsoleConfigInterface
    {
        $configPath = $input->getArgument('config-path');

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
        $factory = new $factoryClass();
        return $factory->invoke($configPath);
    }
}
