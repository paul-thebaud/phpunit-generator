<?php

namespace PhpUnitGen\Console;

use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConsoleConfigInterface;
use PhpUnitGen\Configuration\JsonConsoleConfig;
use PhpUnitGen\Configuration\YamlConsoleConfig;
use PhpUnitGen\Container\DependencyInjectorInterface;
use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Parser\ParserInterface\DirectoryParserInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateTestsCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GenerateTestsCommand extends Command
{
    /**
     * @var ContainerInterface $container A container to manage dependencies.
     */
    private $container;

    /**
     * @var DependencyInjectorInterface $dependencyInjector A tool to inject dependencies in container from config.
     */
    private $dependencyInjector;

    /**
     * @var FilesystemInterface $fileSystem A file system to find configuration file.
     */
    private $fileSystem;

    /**
     * @var DirectoryParserInterface $directoryParser A directory parser to parse each files in directory.
     */
    private $directoryParser;

    /**
     * GenerateTestsCommand constructor.
     *
     * @param ContainerInterface $container A container to manage dependencies.
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container          = $container;
        $this->dependencyInjector = $container->get(DependencyInjectorInterface::class);
        $this->fileSystem         = $container->get(FilesystemInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("generate")
            ->setDescription("Generate unit tests skeletons")
            ->setHelp("Use it to generate your unit tests skeletons")
            ->addArgument('config-path', InputArgument::REQUIRED, 'The config file path.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $config = $this->getConfiguration($input->getArgument('config-path'));
        } catch (InvalidConfigException $exception) {
            $output->writeln(
                sprintf("<error>Error during configuration parsing:\n\n%s</error>", $exception->getMessage())
            );
            return -1;
        }

        // Load the ConfigInterface dependency
        $this->container = $this->dependencyInjector->inject($config, $this->container);

        // Add output
        $this->container->setInstance(OutputInterface::class, $output);

        $this->directoryParser = $this->container->get(DirectoryParserInterface::class);

        /** @todo Extract this in another class */

        $directoryModels = [];
        foreach ($config->getDirectories() as $sourceDirectory => $targetDirectory) {
            try {
                $directoryModels[] = $this->directoryParser->parse($sourceDirectory, $targetDirectory);
            } catch (ParsingException $exception) {
                $output->writeln(sprintf(
                    "<error>Parsing directory \"%s\" failed for the following reason:\n\n%s</error>",
                    $sourceDirectory,
                    $exception->getMessage()
                ));
                if (! $config->hasIgnore()) {
                    return -1;
                }
            }

            $output->writeln(sprintf('<info>Parsing directory "%s" completed.</info>', $sourceDirectory));
        }
    }

    /**
     * Build a configuration from a configuration file path.
     *
     * @param string $path The config file path.
     *
     * @return ConsoleConfigInterface The created configuration.
     *
     * @throws InvalidConfigException If an error occurs during process.
     */
    public function getConfiguration(string $path): ConsoleConfigInterface
    {
        if (! $this->fileSystem->has($path)) {
            throw new InvalidConfigException(sprintf('Config file "%s" does not exists.', $path));
        }

        switch (pathinfo($path, PATHINFO_EXTENSION)) {
            case 'yml':
                $configClass = YamlConsoleConfig::class;
                break;
            case 'json':
                $configClass = JsonConsoleConfig::class;
                break;
            default:
                throw new InvalidConfigException(
                    sprintf('Config file "%s" must have .json or .yml extension.', $path)
                );
        }
        return new $configClass($this->fileSystem->read($path));
    }
}
