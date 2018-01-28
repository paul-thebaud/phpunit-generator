<?php

namespace PhpUnitGen\Annotation;

use Doctrine\Common\Lexer\AbstractLexer;

/**
 * Class Lexer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class Lexer extends AbstractLexer
{
    /**
     * All possible tokens.
     */
    public const T_ANNOTATION    = 0;
    public const T_O_PARENTHESIS = 1;
    public const T_C_PARENTHESIS = 2;
    public const T_BACKSLASH     = 3;
    public const T_SINGLE_QUOTE  = 4;
    public const T_DOUBLE_QUOTE  = 5;
    public const T_ASTERISK      = 6;
    public const T_LINE_BREAK    = 7;
    public const T_WHITESPACE    = 8;
    public const T_OTHER         = 9;

    /**
     * @var array PATTERNS_TOKENS Matching array between regex pattern and token.
     */
    public const PATTERNS_TOKENS = [
        '(@(?i)(PhpUnitGen|Pug)\\\\[a-zA-Z0-9]+)' => Lexer::T_ANNOTATION,
        '(\()'                                    => Lexer::T_O_PARENTHESIS,
        '(\))'                                    => Lexer::T_C_PARENTHESIS,
        '(\\\\)'                                  => Lexer::T_BACKSLASH,
        '(\')'                                    => Lexer::T_SINGLE_QUOTE,
        '(")'                                     => Lexer::T_DOUBLE_QUOTE,
        '(\*)'                                    => Lexer::T_ASTERISK,
        '(\r|\n|\r\n|\n\r)'                       => Lexer::T_LINE_BREAK,
        '(\s)'                                    => Lexer::T_WHITESPACE,
        '([^\\\\"\'\(\)\*\s]+)'                   => Lexer::T_OTHER,
    ];

    /**
     * @var string[] $patterns Array containing regex patterns.
     */
    private $patterns;

    /**
     * Lexer constructor.
     */
    public function __construct()
    {
        $this->patterns = array_keys(Lexer::PATTERNS_TOKENS);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCatchablePatterns(): array
    {
        return $this->patterns;
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value): int
    {
        foreach (Lexer::PATTERNS_TOKENS as $pattern => $token) {
            if (preg_match($pattern, $value)) {
                return $token;
            }
        }
        return Lexer::T_OTHER;
    }
}
