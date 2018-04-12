<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;

/**
 * Class TokenConsumer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class TokenConsumer
{
    /**
     * @var AnnotationFactory $annotationFactory The annotation factory to use.
     */
    private $annotationFactory;

    /**
     * @var AnnotationInterface[] $parsedAnnotations The parsed annotation list.
     */
    private $parsedAnnotations;

    /**
     * @var int $currentLine The current line number.
     */
    private $currentLine;

    /**
     * @var AbstractAnnotation $currentAnnotation The current parsed annotation, null if none.
     */
    private $currentAnnotation;

    /**
     * @var string|null $currentAnnotationContent The current parsed annotation content, null if none.
     */
    private $currentAnnotationContent;

    /**
     * @var int|null $openedStringToken The opened string opening token identifier, null if no string opened.
     */
    private $openedStringToken;

    /**
     * @var bool $currentlyEscaping Tells if last parsed token was an escape token.
     */
    private $currentlyEscaping;

    /**
     * @var int $openedParenthesis The opened parenthesis number for an annotation content.
     */
    private $openedParenthesis;

    /**
     * TokenConsumer constructor.
     *
     * @param AnnotationFactory $annotationFactory The annotation factory to use.
     */
    public function __construct(AnnotationFactory $annotationFactory)
    {
        $this->annotationFactory = $annotationFactory;
        $this->parsedAnnotations = [];
    }

    /**
     * Initialize the documentation parsing process.
     */
    public function initialize(): void
    {
        $this->parsedAnnotations = [];
        $this->currentLine       = 0;
        $this->reset();
    }

    /**
     * Reset an annotation parsing.
     */
    public function reset(): void
    {
        $this->currentAnnotation        = null;
        $this->currentAnnotationContent = null;
        $this->openedStringToken        = null;
        $this->currentlyEscaping        = false;

        $this->openedParenthesis = 0;
    }

    /**
     * Finalize the documentation parsing process.
     *
     * @throws AnnotationParseException If an annotation content is not closed.
     */
    public function finalize(): void
    {
        if ($this->currentAnnotation !== null) {
            if ($this->currentAnnotationContent === null) {
                $this->parsedAnnotations[] = $this->currentAnnotation;
            } else {
                throw new AnnotationParseException(
                    'An annotation content is not closed (you probably forget to close a parenthesis or a quote)'
                );
            }
        }
    }

    /**
     * Add a token content to the current annotation content.
     *
     * @param string $value The token value to add.
     */
    public function addTokenToContent(string $value)
    {
        if ($this->currentAnnotationContent !== null) {
            $this->currentAnnotationContent .= $value;
        }
    }

    /**
     * Consume an annotation token ("@PhpUnitGen" for example).
     *
     * @param string $value The annotation token value.
     *
     * @throws AnnotationParseException If the annotation is unknown.
     */
    public function consumeAnnotationToken(string $value): void
    {
        if ($this->currentAnnotation === null) {
            // We are not in an annotation, build a new one.
            $this->currentAnnotation = $this->annotationFactory
                ->invoke($value, $this->currentLine);
        } else {
            if ($this->currentAnnotationContent === null) {
                // It is an annotation without content, save it and create the new one.
                $this->parsedAnnotations[] = $this->currentAnnotation;
                $this->reset();
                $this->currentAnnotation = $this->annotationFactory
                    ->invoke($value, $this->currentLine);
            } else {
                // An annotation content parsing is not finished.
                throw new AnnotationParseException(
                    'An annotation content is not closed (you probably forget to close a parenthesis or a quote)'
                );
            }
        }
    }

    /**
     * Consume an opening parenthesis token.
     */
    public function consumeOpeningParenthesisToken(): void
    {
        if ($this->currentAnnotation !== null && $this->openedStringToken === null) {
            // We are in an annotation but not in a string, lets do something.
            if ($this->currentAnnotationContent === null) {
                if ($this->currentAnnotation->getLine() === $this->currentLine) {
                    // Begin content parsing only if it is on the same line.
                    $this->currentAnnotationContent = '';
                    $this->openedParenthesis++;
                }
            } else {
                $this->openedParenthesis++;
            }
        }
    }

    /**
     * Consume an closing parenthesis token.
     */
    public function consumeClosingParenthesisToken(): void
    {
        if ($this->currentAnnotationContent !== null && $this->openedStringToken === null) {
            // We are in an annotation content and not in a string.
            if ($this->openedParenthesis > 0) {
                $this->openedParenthesis--;
                if ($this->openedParenthesis === 0) {
                    // Annotation content is finished.
                    $this->currentAnnotation->setStringContent(substr(
                        $this->currentAnnotationContent,
                        1,
                        strlen($this->currentAnnotationContent) - 2
                    ));
                    $this->parsedAnnotations[] = $this->currentAnnotation;
                    $this->reset();
                }
            }
        }
    }

    /**
     * Consume a quote token (" or ').
     *
     * @param int $type The type of the quote token.
     */
    public function consumeQuoteToken(int $type): void
    {
        if ($this->currentAnnotationContent !== null) {
            if ($this->openedStringToken === null) {
                // It is a string opening.
                $this->openedStringToken = $type;
            } else {
                if (! $this->currentlyEscaping && $type === $this->openedStringToken) {
                    // We are in a string, the token is not escaped and is the same as the string opening token.
                    // Close the string.
                    $this->openedStringToken = null;
                }
            }
        }
    }

    /**
     * A method that is executed after consuming a token.
     *
     * @param int $type The token type (an integer from the Lexer class constant).
     */
    public function afterConsume(int $type): void
    {
        // Put in escaping mode if it were not escaping and there is a backslash token.
        if (! $this->currentlyEscaping && $type === AnnotationLexer::T_BACKSLASH) {
            $this->currentlyEscaping = true;
        } else {
            $this->currentlyEscaping = false;
        }
    }

    /**
     * @return bool True if a string is currently opened.
     */
    public function hasOpenedString(): bool
    {
        return $this->openedStringToken !== null;
    }

    /**
     * Increase the line number.
     */
    public function increaseLine(): void
    {
        $this->currentLine++;
    }

    /**
     * @return AnnotationInterface[] The parsed annotations.
     */
    public function getParsedAnnotations(): array
    {
        return $this->parsedAnnotations;
    }
}
