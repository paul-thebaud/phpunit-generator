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

use Doctrine\Common\Lexer\AbstractLexer;
use PhpUnitGen\Exception\Exception;

/**
 * Class AnnotationLexer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AnnotationLexer extends AbstractLexer
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
        '(@(?i)(PhpUnitGen|Pug)\\\\[a-zA-Z0-9]+)' => AnnotationLexer::T_ANNOTATION,
        '(\()'                                    => AnnotationLexer::T_O_PARENTHESIS,
        '(\))'                                    => AnnotationLexer::T_C_PARENTHESIS,
        '(\\\\)'                                  => AnnotationLexer::T_BACKSLASH,
        '(\')'                                    => AnnotationLexer::T_SINGLE_QUOTE,
        '(")'                                     => AnnotationLexer::T_DOUBLE_QUOTE,
        '(\*)'                                    => AnnotationLexer::T_ASTERISK,
        '(\r|\n|\r\n|\n\r)'                       => AnnotationLexer::T_LINE_BREAK,
        '(\s)'                                    => AnnotationLexer::T_WHITESPACE,
        '([^\\\\"\'\(\)\*\s]+)'                   => AnnotationLexer::T_OTHER,
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
        $this->patterns = array_keys(AnnotationLexer::PATTERNS_TOKENS);
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
        foreach ($this->getPatternsToken() as $pattern => $token) {
            if (preg_match($pattern, $value)) {
                return $token;
            }
        }
        throw new Exception('Invalid value given to lexer');
    }

    /**
     * @return array All matchable patterns with corresponding tokens identifiers.
     */
    protected function getPatternsToken(): array
    {
        return AnnotationLexer::PATTERNS_TOKENS;
    }
}
