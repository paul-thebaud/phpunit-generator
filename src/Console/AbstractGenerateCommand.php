<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Configuration\JsonConsoleConfigFactory;
use PhpUnitGen\Configuration\PhpConsoleConfigFactory;
use PhpUnitGen\Configuration\YamlConsoleConfigFactory;
use PhpUnitGen\Container\ContainerInterface\ConsoleContainerFactoryInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\InvalidConfigException;
use PhpUnitGen\Executor\ExecutorInterface\ConsoleExecutorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

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
     */
    public function __construct(ConsoleContainerFactoryInterface $containerFactory)
    {
        parent::__construct();

        $this->containerFactory = $containerFactory;

        $this->stopwatch = new Stopwatch(true);
        $this->stopwatch->start('command');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $styledOutput = new SymfonyStyle($input, $output);
        try {
            $config = $this->getConfiguration($input);

            $container = $this->containerFactory->invoke($config, $styledOutput, $this->stopwatch);

            $this->consoleExecutor = $container->get(ConsoleExecutorInterface::class);

            $this->consoleExecutor->invoke();
        } catch (Exception $exception) {
            $styledOutput->error($exception->getMessage());
            return -1;
        }

        return 1;
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
    abstract protected function getConfiguration(InputInterface $input): ConsoleConfigInterface;
}
