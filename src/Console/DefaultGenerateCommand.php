<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\ConsoleConfig;
use PhpUnitGen\Configuration\ConsoleConfigFactoryInterface;
use PhpUnitGen\Configuration\ConsoleConfigInterface;
use PhpUnitGen\Configuration\DefaultConsoleConfigFactory;
use PhpUnitGen\Configuration\JsonConsoleConfigFactory;
use PhpUnitGen\Configuration\PhpConsoleConfigFactory;
use PhpUnitGen\Configuration\YamlConsoleConfigFactory;
use PhpUnitGen\Exception\InvalidConfigException;
use Respect\Validation\Validator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class DefaultGenerateCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DefaultGenerateCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("generate-default")
            ->setDescription("Generate unit tests skeletons")
            ->setHelp("Use it to generate your unit tests skeletons from a default config")
            ->addArgument('source-path', InputArgument::REQUIRED, 'The source directory path.')
            ->addArgument('target-path', InputArgument::REQUIRED, 'The target directory path.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(InputInterface $input): ConsoleConfigInterface
    {
        $sourceDirectory = $input->getArgument('source-path');
        $targetDirectory = $input->getArgument('target-path');

        /** @var ConsoleConfigFactoryInterface $factory */
        $factory = new DefaultConsoleConfigFactory();
        return $factory->invoke($sourceDirectory, $targetDirectory);
    }
}
