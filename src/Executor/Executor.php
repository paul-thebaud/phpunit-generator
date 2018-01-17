<?php

namespace PhpUnitGen\Executor;

use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;

/**
 * Class Executor.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class Executor implements ExecutorInterface
{
    /**
     * @var ConfigInterface $config The configuration to use.
     */
    private $config;

    /**
     * @var PhpParserInterface $phpFileParser The php file parser.
     */
    private $phpFileParser;

    /**
     * @var PhpFileRendererInterface $phpFileRenderer The php file renderer.
     */
    private $phpFileRenderer;

    /**
     * Executor constructor.
     *
     * @param ConfigInterface          $config          The config instance.
     * @param PhpParserInterface       $phpFileParser   The php file parser.
     * @param PhpFileRendererInterface $phpFileRenderer The php file renderer.
     */
    public function __construct(
        ConfigInterface $config,
        PhpParserInterface $phpFileParser,
        PhpFileRendererInterface $phpFileRenderer
    ) {
        $this->config          = $config;
        $this->phpFileParser   = $phpFileParser;
        $this->phpFileRenderer = $phpFileRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(string $code, string $name = 'GeneratedTest'): ?string
    {
        $phpFile = $this->phpFileParser->invoke($code);
        $phpFile->setName($name);

        if ($phpFile->getTestableFunctionsCount() === 0) {
            if (! $this->config->hasInterfaceParsing() || $phpFile->getInterfacesFunctionsCount() === 0) {
                return null;
            }
        }

        return $this->phpFileRenderer->invoke($phpFile);
    }
}
