<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Container;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Container\ContainerInterface\ContainerFactoryInterface;
use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Parser\PhpParser;
use PhpUnitGen\Renderer\PhpFileRenderer;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;
use PhpUnitGen\Report\Report;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;

/**
 * Class ContainerFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(
        ConfigInterface $config
    ): ContainerInterface {
        $container = new Container();

        // Configuration
        $container->setInstance(ConfigInterface::class, $config);
        $container->setInstance(ConsoleConfigInterface::class, $config);
        // Php parser
        $container->setInstance(Parser::class, (new ParserFactory())->create(ParserFactory::PREFER_PHP7));
        // Php renderer
        $container->setInstance(PhpRenderer::class, new PhpRenderer($config->getTemplatesPath()));

        // Automatically created dependencies and aliases
        $container->set(PhpParserInterface::class, PhpParser::class);
        $container->set(ExecutorInterface::class, Executor::class);
        $container->set(ReportInterface::class, Report::class);
        $container->set(PhpFileRendererInterface::class, PhpFileRenderer::class);

        return $container;
    }
}
