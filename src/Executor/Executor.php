<?php

namespace PhpUnitGen\Executor;

use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;
use PhpUnitGen\Parser\ParserInterface\PhpFileParserInterface;

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
     * @var PhpFileParserInterface $phpFileParser The php file parser.
     */
    private $phpFileParser;

    /**
     * Executor constructor.
     *
     * @param PhpFileParserInterface $phpFileParser The php file parser.
     */
    public function __construct(
        PhpFileParserInterface $phpFileParser
    ) {
        $this->phpFileParser = $phpFileParser;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $code): string
    {
        $phpFileModel = $this->phpFileParser->parse($code);

        /** @todo ... */

        return '';
    }
}
