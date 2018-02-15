<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Exception;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\ExceptionInterface\ExceptionCatcherInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class ExceptionCatcher.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ExceptionCatcher implements ExceptionCatcherInterface
{
    /**
     * @var ConsoleConfigInterface $config The configuration to use.
     */
    private $config;

    /**
     * @var StyleInterface $output The output to display message.
     */
    private $output;

    /**
     * ExceptionCatcher constructor.
     *
     * @param ConsoleConfigInterface $config The config to use.
     * @param StyleInterface         $output The output to use.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        StyleInterface $output
    ) {
        $this->config = $config;
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function catch(Exception $exception, string $path): void
    {
        if ($this->config->hasIgnore()
            && $exception instanceof IgnorableException
        ) {
            $this->output->note(sprintf('On file "%s": %s', $path, $exception->getMessage()));
        } else {
            throw $exception;
        }
    }
}
