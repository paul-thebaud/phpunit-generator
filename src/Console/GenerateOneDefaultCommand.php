<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Configuration\DefaultConsoleConfigFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class GenerateOneDefaultCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GenerateOneDefaultCommand extends AbstractGenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("generate-one-default")
            ->setDescription("Generate unit tests skeletons with a default configuration for only one file")
            ->setHelp("Use it to generate your unit tests skeletons from a default config")
            ->addArgument('source-file-path', InputArgument::REQUIRED, 'The source file path.')
            ->addArgument('target-file-path', InputArgument::REQUIRED, 'The target file path.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(InputInterface $input): ConsoleConfigInterface
    {
        $sourceFile = $input->getArgument('source-file-path');
        $targetFile = $input->getArgument('target-file-path');

        $factory = new DefaultConsoleConfigFactory();
        return $factory->invokeOneFile($sourceFile, $targetFile);
    }
}
