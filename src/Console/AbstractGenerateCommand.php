<?php

namespace PhpUnitGen\Console;

use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Parser\ParserInterface\DirectoryParserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractGenerateCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractGenerateCommand extends Command
{
    /**
     * @var ConsoleContainerFactoryInterface $containerFactory A container factory to create container.
     */
    protected $containerFactory;

    /**
     * @var DirectoryParserInterface $directoryParser A directory parser to parse each files in directory.
     */
    protected $directoryParser;

    /**
     * @var FilesystemInterface $fileSystem A file system to navigate through files.
     */
    protected $fileSystem;

    /**
     * GenerateCommand constructor.
     *
     * @param ConsoleContainerFactoryInterface $containerFactory A container factory to create container.
     */
    public function __construct(ConsoleContainerFactoryInterface $containerFactory)
    {
        parent::__construct();

        $this->containerFactory = $containerFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $config = $this->getConfiguration($input);
        } catch (InvalidConfigException $exception) {
            $output->writeln(
                sprintf("<error>Error during configuration parsing:\n\n%s</error>", $exception->getMessage())
            );
            return -1;
        }

        $container = $this->containerFactory->invoke($config, $output);

        $this->directoryParser = $container->get(DirectoryParserInterface::class);
        $this->fileSystem      = $container->get(FilesystemInterface::class);

        foreach ($config->getDirectories() as $sourceDirectory => $targetDirectory) {
            if ($this->generateForDirectory($output, $config, $sourceDirectory, $targetDirectory) === false) {
                return -1;
            }
        }
        foreach ($config->getFiles() as $sourceFile => $targetFile) {
            /** @todo move this and generate for files too */
            $output->writeln(sprintf("<info>Hey! Request parsing of file: %s</info>", $sourceFile));
        }

        return 1;
    }

    /**
     * Generate unit tests skeletons for a source directory to a target directory.
     *
     * @param OutputInterface        $output          An output to display message.
     * @param ConsoleConfigInterface $config          A configuration.
     * @param string                 $sourceDirectory A source directory to get files to parse.
     * @param string                 $targetDirectory A target directory to put generated files.
     *
     * @return bool True there was no (important) error during parsing.
     */
    protected function generateForDirectory(
        OutputInterface $output,
        ConsoleConfigInterface $config,
        string $sourceDirectory,
        string $targetDirectory
    ): bool {
        try {
            $directoryModel = $this->directoryParser->parse($sourceDirectory, $targetDirectory);
        } catch (ParsingException $exception) {
            $output->writeln(sprintf(
                "<error>Parsing directory \"%s\" failed for the following reason:\n\n%s</error>",
                $sourceDirectory,
                $exception->getMessage()
            ));
            if (! $config->hasIgnore()) {
                return false;
            }
        }

        /** @todo Parse the $directoryModel to generate unit tests skeletons */

        $output->writeln(sprintf('<info>Parsing directory "%s" completed.</info>', $sourceDirectory));

        return true;
    }

    /**
     * Build a configuration from a configuration file path.
     *
     * @param InputInterface $input An input interface to retrieve command argument.
     *
     * @return ConsoleConfigInterface The created configuration.
     *
     * @throws InvalidConfigException If an error occurs during process.
     */
    protected abstract function getConfiguration(InputInterface $input): ConsoleConfigInterface;
}
