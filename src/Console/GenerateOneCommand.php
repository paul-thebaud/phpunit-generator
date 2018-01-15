<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigFactoryInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\InvalidConfigException;
use Respect\Validation\Validator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class GenerateOneCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GenerateOneCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("generate-one")
            ->setDescription("Generate unit tests skeletons with a custom configuration for only one file")
            ->setHelp("Use it to generate your unit tests skeletons from a configuration file and for only one file")
            ->addArgument('source-file-path', InputArgument::REQUIRED, 'The source file path.')
            ->addArgument('target-file-path', InputArgument::REQUIRED, 'The target file path.')
            ->addArgument('config-path', InputArgument::OPTIONAL, 'The configuration file path.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(InputInterface $input): ConsoleConfigInterface
    {
        $configPath = 'phpunitgen.yml';
        if (($inputConfigPath = $input->getArgument('config-path')) !== null) {
            $configPath = $inputConfigPath;
        }
        $sourceFile = $input->getArgument('source-file-path');
        $targetFile = $input->getArgument('target-file-path');

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
        return $factory->invokeOneFile($configPath, $sourceFile, $targetFile);
    }
}
