<?php

namespace PhpUnitGen\Console;

use PhpUnitGen\Container\ConsoleContainerFactory;
use Symfony\Component\Console\Application as AbstractApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct('phpunitgen', '2.0.0');

        $containerFactory = new ConsoleContainerFactory();

        $this->add(new GenerateCommand($containerFactory));
        $this->add(new DefaultGenerateCommand($containerFactory));
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (! $output->isQuiet()) {
            $output->writeln(sprintf(
                "phpunitgen %s by Paul Thébaud.\n\n",
                $this->getVersion()
            ));
        }

        if ($input->hasParameterOption('--version') ||
            $input->hasParameterOption('-V')) {
            return 0;
        }

        parent::doRun($input, $output);
    }
}
