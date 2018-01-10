<?php

namespace PhpUnitGen\Container;

use League\Flysystem\FilesystemInterface;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpUnitGen\Configuration\ConfigInterface;
use PhpUnitGen\Parser\DirectoryParser;
use PhpUnitGen\Parser\ParserInterface\DirectoryParserInterface;
use PhpUnitGen\Parser\ParserInterface\PhpFileParserInterface;
use PhpUnitGen\Parser\PhpFileParser;
use Psr\Container\ContainerInterface;

/**
 * Class DependencyInjector.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DependencyInjector implements DependencyInjectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function inject(ConfigInterface $config, ContainerInterface $container): ContainerInterface
    {
        /** @var Container $container */
        $container->setInstance(ConfigInterface::class, $config);

        $container->setInstance(Parser::class, (new ParserFactory())->create(ParserFactory::PREFER_PHP7));

        $container->setResolver(PhpFileParserInterface::class, function (ContainerInterface $container) {
            return new PhpFileParser($container->get(Parser::class));
        });

        $container->setResolver(DirectoryParserInterface::class, function (ContainerInterface $container) {
            return new DirectoryParser(
                $container->get(ConfigInterface::class),
                $container->get(FilesystemInterface::class),
                $container->get(PhpFileParserInterface::class)
            );
        });

        return $container;
    }
}
