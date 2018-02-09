<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Comment\Doc;
use PhpUnitGen\Annotation\AbstractAnnotation;
use PhpUnitGen\Annotation\AnnotationFactory;
use PhpUnitGen\Annotation\AnnotationRegister;
use PhpUnitGen\Annotation\Lexer;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\PropertyInterface\DocumentationInterface;

/**
 * Class DocumentationNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DocumentationNodeParser
{
    /**
     * @var Lexer $lexer The lexer to use.
     */
    private $lexer;

    /**
     * @var AnnotationFactory $annotationFactory The annotation factory to use.
     */
    private $annotationFactory;

    /**
     * @var AnnotationRegister $annotationRegister The annotation register to use.
     */
    private $annotationRegister;

    /**
     * @var AbstractAnnotation[] $parsedAnnotations The parsed annotation list.
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
     * DocumentationNodeParser constructor.
     *
     * @param Lexer              $lexer              The lexer to use.
     * @param AnnotationFactory  $annotationFactory  The annotation factory to use.
     * @param AnnotationRegister $annotationRegister The annotation register to use.
     */
    public function __construct(
        Lexer $lexer,
        AnnotationFactory $annotationFactory,
        AnnotationRegister $annotationRegister
    ) {
        $this->lexer              = $lexer;
        $this->annotationFactory  = $annotationFactory;
        $this->annotationRegister = $annotationRegister;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Doc                    $node   The node to parse.
     * @param DocumentationInterface $parent The parent node.
     *
     * @throws AnnotationParseException If an annotation is invalid.
     */
    public function invoke(Doc $node, DocumentationInterface $parent): void
    {
        $documentation = $node->getText();
        $parent->setDocumentation($documentation);

        try {
            $this->initialize();

            $this->lexer->setInput($documentation);
            $this->lexer->moveNext();
            $this->lexer->moveNext();

            while ($this->lexer->token) {
                $this->parse(
                    $this->lexer->token['type'],
                    $this->lexer->token['value']
                );
                $this->lexer->moveNext();
                $this->lexer->moveNext();
            }

            if ($this->currentAnnotation !== null) {
                if ($this->currentAnnotationContent === null) {
                    $this->parsedAnnotations[] = $this->currentAnnotation;
                } else {
                    throw new AnnotationParseException(
                        'An annotation content is not closed (you probably forget to close a parenthesis or a quote)'
                    );
                }
            }

            $this->annotationRegister->invoke($parent, $this->parsedAnnotations);
        } catch (AnnotationParseException $exception) {
            throw new AnnotationParseException($exception->getMessage());
        }
    }

    /**
     * Initialize the documentation parsing process.
     */
    private function initialize(): void
    {
        $this->parsedAnnotations = [];
        $this->currentLine       = 0;
        $this->reset();
    }

    /**
     * Reset an annotation parsing.
     */
    private function reset(): void
    {
        $this->currentAnnotation        = null;
        $this->currentAnnotationContent = null;
        $this->openedStringToken        = null;
        $this->currentlyEscaping        = false;

        $this->openedParenthesis = 0;
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
            case Lexer::T_ANNOTATION:
                $this->consumeAnnotationToken($value);
                break;
            case Lexer::T_O_PARENTHESIS:
                $this->consumeOpeningParenthesisToken();
                $this->addTokenToContent($value);
                break;
            case Lexer::T_C_PARENTHESIS:
                $this->addTokenToContent($value);
                $this->consumeClosingParenthesisToken();
                break;
            case Lexer::T_SINGLE_QUOTE:
            case Lexer::T_DOUBLE_QUOTE:
                $this->addTokenToContent($value);
                $this->consumeQuoteToken($type);
                break;
            case Lexer::T_ASTERISK:
                if ($this->openedStringToken !== null) {
                    // We are in a string, save this token value.
                    $this->addTokenToContent($value);
                }
                break;
            case Lexer::T_LINE_BREAK:
                $this->addTokenToContent($value);
                $this->currentLine++;
                break;
            case Lexer::T_BACKSLASH:
            case Lexer::T_WHITESPACE:
            case Lexer::T_OTHER:
                $this->addTokenToContent($value);
                break;
            default:
                throw new AnnotationParseException(sprintf('A token of value "%s" has an invalid type', $value));
        }
        $this->afterConsume($type);
    }

    /**
     * Add a token content to the current annotation content.
     *
     * @param string $value The token value to add.
     */
    private function addTokenToContent(string $value)
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
    private function consumeAnnotationToken(string $value): void
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
    private function consumeOpeningParenthesisToken(): void
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
    private function consumeClosingParenthesisToken(): void
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
     */
    private function consumeQuoteToken(int $type): void
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
    private function afterConsume(int $type): void
    {
        // Put in escaping mode if it were not escaping and there is a backslash token.
        if (! $this->currentlyEscaping && $type === Lexer::T_BACKSLASH) {
            $this->currentlyEscaping = true;
        } else {
            $this->currentlyEscaping = false;
        }
    }
}
