<?php

namespace PhpUnitGen\Executor;

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
     * @param PhpParserInterface       $phpFileParser   The php file parser.
     * @param PhpFileRendererInterface $phpFileRenderer The php file renderer.
     */
    public function __construct(
        PhpParserInterface $phpFileParser,
        PhpFileRendererInterface $phpFileRenderer
    ) {
        $this->phpFileParser   = $phpFileParser;
        $this->phpFileRenderer = $phpFileRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(string $code, string $name = 'GeneratedTest'): string
    {
        $phpFileModel = $this->phpFileParser->invoke($code);
        $phpFileModel->setName($name);

        return $this->phpFileRenderer->invoke($phpFileModel);
    }
}
