<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\AbstractConsoleConfigFactory;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Configuration\DefaultConsoleConfigFactory;
use PhpUnitGen\Configuration\JsonConsoleConfigFactory;
use PhpUnitGen\Configuration\PhpConsoleConfigFactory;
use PhpUnitGen\Configuration\YamlConsoleConfigFactory;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use Respect\Validation\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class GenerateCommand.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GenerateCommand extends Command
{
    public const STOPWATCH_EVENT = 'command';

    /**
     * @var string[] CONSOLE_CONFIG_FACTORIES Mapping array between file extension and configuration factories.
     */
    protected const CONSOLE_CONFIG_FACTORIES = [
        'yml'  => YamlConsoleConfigFactory::class,
        'json' => JsonConsoleConfigFactory::class,
        'php'  => PhpConsoleConfigFactory::class
    ];

    /**
     * @var ConsoleContainerFactoryInterface $containerFactory A container factory to create container.
     */
    protected $containerFactory;

    /**
     * @var ConsoleExecutorInterface $consoleExecutor A executor to execute PhpUnitGen task.
     */
    protected $consoleExecutor;

    /**
     * @var Stopwatch $stopwatch The stopwatch to measure duration and memory usage.
     */
    protected $stopwatch;

    /**
     * GenerateCommand constructor.
     *
     * @param ConsoleContainerFactoryInterface $containerFactory A container factory to create container.
     * @param Stopwatch                        $stopwatch        The stopwatch component for execution stats.
     */
    public function __construct(ConsoleContainerFactoryInterface $containerFactory, Stopwatch $stopwatch)
    {
        parent::__construct();

        $this->containerFactory = $containerFactory;
        $this->stopwatch        = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('generate')
            ->setAliases(['gen'])
            ->setDescription('Generate unit tests skeletons.')
            ->setHelp(
                'Use it to generate your unit tests skeletons. See documentation on ' .
                'https://github.com/paul-thebaud/phpunit-generator/blob/master/DOCUMENTATION.md'
            )
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'The configuration file path.', 'phpunitgen.yml')
            ->addOption('file', 'f', InputOption::VALUE_NONE, 'If you want file parsing.')
            ->addOption('dir', 'd', InputOption::VALUE_NONE, 'If you want directory parsing.')
            ->addOption('default', 'D', InputOption::VALUE_NONE, 'If you want to use the default configuration.')
            ->addArgument('source', InputArgument::OPTIONAL, 'The source path (directory if "dir" option set).')
            ->addArgument('target', InputArgument::OPTIONAL, 'The target path (directory if "dir" option set).');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->stopwatch->start(GenerateCommand::STOPWATCH_EVENT);

        $styledIO = $this->getStyledIO($input, $output);
        try {
            $config = $this->getConfiguration($input);

            $container = $this->containerFactory->invoke($config, $styledIO, $this->stopwatch);

            $this->consoleExecutor = $container->get(ConsoleExecutorInterface::class);

            $this->consoleExecutor->invoke();
        } catch (Exception $exception) {
            $styledIO->error($exception->getMessage());
            return -1;
        }

        return 0;
    }

    /**
     * Build a new instance of SymfonyStyle.
     *
     * @param InputInterface  $input  The input.
     * @param OutputInterface $output The output.
     *
     * @return SymfonyStyle The created symfony style i/o.
     */
    protected function getStyledIO(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        return new SymfonyStyle($input, $output);
    }

    /**
     * Build a configuration from a configuration file path.
     *
     * @param InputInterface $input An input interface to retrieve command argument.
     *
     * @return ConsoleConfigInterface The created configuration.
     *
     * @throws Exception If an error occurs during process.
     */
    protected function getConfiguration(InputInterface $input): ConsoleConfigInterface
    {
        // If the configuration to use is the default.
        if ($input->getOption('default')) {
            $this->validatePathsExist($input);
            // If it is a directory.
            if ($input->getOption('dir')) {
                return (new DefaultConsoleConfigFactory())
                    ->invokeDir($input->getArgument('source'), $input->getArgument('target'));
            }
            return (new DefaultConsoleConfigFactory())
                ->invokeFile($input->getArgument('source'), $input->getArgument('target'));
        }

        $path = $input->getOption('config');
        // If we have a "=" at the beginning of the option value
        $path = preg_replace('/^\=/', '', $path);

        $factory = $this->getConfigurationFactory($path);

        if ($input->getOption('dir')) {
            $this->validatePathsExist($input);
            return $factory->invokeDir(
                $path,
                $input->getArgument('source'),
                $input->getArgument('target')
            );
        }
        if ($input->getOption('file')) {
            $this->validatePathsExist($input);
            return $factory->invokeFile(
                $path,
                $input->getArgument('source'),
                $input->getArgument('target')
            );
        }
        return $factory->invoke($path);
    }

    /**
     * Get the configuration factory depending on configuration path.
     *
     * @param string $path The configuration file path.
     *
     * @return AbstractConsoleConfigFactory The configuration factory.
     *
     * @throws InvalidConfigException If the configuration is invalid.
     */
    protected function getConfigurationFactory(string $path): AbstractConsoleConfigFactory
    {
        if (! file_exists($path)) {
            throw new InvalidConfigException(sprintf('Config file "%s" does not exists', $path));
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if (! Validator::key($extension)->validate(static::CONSOLE_CONFIG_FACTORIES)) {
            throw new InvalidConfigException(
                sprintf('Config file "%s" must have .yml, .json or .php extension', $path)
            );
        }

        /** @var AbstractConsoleConfigFactory $factory */
        $factoryClass = static::CONSOLE_CONFIG_FACTORIES[$extension];
        return new $factoryClass();
    }

    /**
     * Throw an exception if the source or the target path is missing.
     *
     * @param InputInterface $input The input interface to check.
     *
     * @throws Exception If the source or the target path is missing.
     */
    protected function validatePathsExist(InputInterface $input): void
    {
        if (! is_string($input->getArgument('source'))) {
            throw new Exception('Missing the source path');
        }
        if (! is_string($input->getArgument('target'))) {
            throw new Exception('Missing the target path');
        }
    }
}
