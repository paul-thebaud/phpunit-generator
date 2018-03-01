<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\PropertyInterface\DocumentationInterface;

/**
 * Class AnnotationParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AnnotationParser
{
    /**
     * @var AnnotationLexer $annotationLexer The annotation lexer to use.
     */
    private $annotationLexer;

    /**
     * @var TokenConsumer $tokenConsumer The token consumer to use.
     */
    private $tokenConsumer;

    /**
     * @var AnnotationRegister $annotationRegister The annotation register to use.
     */
    private $annotationRegister;

    /**
     * AnnotationParser constructor.
     *
     * @param AnnotationLexer    $annotationLexer    The lexer to use.
     * @param TokenConsumer      $tokenConsumer      The token consumer to use.
     * @param AnnotationRegister $annotationRegister The annotation register to use.
     */
    public function __construct(
        AnnotationLexer $annotationLexer,
        TokenConsumer $tokenConsumer,
        AnnotationRegister $annotationRegister
    ) {
        $this->annotationLexer    = $annotationLexer;
        $this->tokenConsumer      = $tokenConsumer;
        $this->annotationRegister = $annotationRegister;
    }

    /**
     * Parse a documentation to add annotations to parent.
     *
     * @param DocumentationInterface $parent        The parent that has this documentation.
     * @param string                 $documentation The documentation string to parse.
     *
     * @throws AnnotationParseException If there is an error during parsing process.
     */
    public function invoke(DocumentationInterface $parent, string $documentation): void
    {
        try {
            $this->tokenConsumer->initialize();

            $this->annotationLexer->setInput($documentation);
            $this->annotationLexer->moveNext();
            $this->annotationLexer->moveNext();

            while ($this->annotationLexer->token) {
                $this->parse(
                    $this->annotationLexer->token['type'],
                    $this->annotationLexer->token['value']
                );
                $this->annotationLexer->moveNext();
                $this->annotationLexer->moveNext();
            }

            $this->tokenConsumer->finalize();

            $this->annotationRegister->invoke($parent, $this->tokenConsumer->getParsedAnnotations());
        } catch (AnnotationParseException $exception) {
            throw new AnnotationParseException($exception->getMessage());
        }
    }

    /**
     * Parse a token with a value and type, and consume it depending on type.
     *
     * @param int    $type  The token type (an integer from the Lexer class constant).
     * @param string $value The token value.
     *
     * @throws AnnotationParseException If the token type is invalid.
     */
    private function parse(int $type, string $value): void
    {
        switch ($type) {
            case AnnotationLexer::T_ANNOTATION:
                $this->tokenConsumer->consumeAnnotationToken($value);
                break;
            case AnnotationLexer::T_O_PARENTHESIS:
                $this->tokenConsumer->consumeOpeningParenthesisToken();
                $this->tokenConsumer->addTokenToContent($value);
                break;
            case AnnotationLexer::T_C_PARENTHESIS:
                $this->tokenConsumer->addTokenToContent($value);
                $this->tokenConsumer->consumeClosingParenthesisToken();
                break;
            case AnnotationLexer::T_SINGLE_QUOTE:
            case AnnotationLexer::T_DOUBLE_QUOTE:
                $this->tokenConsumer->addTokenToContent($value);
                $this->tokenConsumer->consumeQuoteToken($type);
                break;
            case AnnotationLexer::T_ASTERISK:
                if ($this->tokenConsumer->hasOpenedString()) {
                    // We are in a string, save this token value.
                    $this->tokenConsumer->addTokenToContent($value);
                }
                break;
            case AnnotationLexer::T_LINE_BREAK:
                $this->tokenConsumer->addTokenToContent($value);
                $this->tokenConsumer->increaseLine();
                break;
            case AnnotationLexer::T_BACKSLASH:
            case AnnotationLexer::T_WHITESPACE:
            case AnnotationLexer::T_OTHER:
                $this->tokenConsumer->addTokenToContent($value);
                break;
            default:
                throw new AnnotationParseException(sprintf('A token of value "%s" has an invalid type', $value));
        }
        $this->tokenConsumer->afterConsume($type);
    }
}
