<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Console;

use PhpUnitGen\Container\ConsoleContainerFactory;
use PhpUnitGen\Container\ContainerFactory;
use Symfony\Component\Console\Application as AbstractApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class Application.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class Application extends AbstractApplication
{
    /**
     * @var string VERSION The current application version.
     */
    public const VERSION = '2.1.4';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct('phpunitgen', static::VERSION);

        $containerFactory = new ConsoleContainerFactory(new ContainerFactory());
        $stopwatch        = new Stopwatch();

        $this->add(new GenerateCommand($containerFactory, $stopwatch));

        $this->setDefaultCommand('generate', true);
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        if (! $output->isQuiet()) {
            $output->writeln(sprintf(
                "PhpUnitGen by Paul Thébaud (version <info>%s</info>).\n",
                $this->getVersion()
            ));
        }

        return $this->doRunParent($input, $output);
    }

    /**
     * Run the "doRun" from parent.
     *
     * @param InputInterface  $input  An input interface.
     * @param OutputInterface $output An output interface.
     *
     * @return int 0 if everything went fine, or an error code.
     *
     * @throws \Throwable If there is an exception during application run.
     */
    protected function doRunParent(InputInterface $input, OutputInterface $output): int
    {
        return parent::doRun($input, $output);
    }
}
