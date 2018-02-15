<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\ParserInterface;

use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Interface PhpParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface PhpParserInterface
{
    /**
     * Parse php code to build a php file model.
     *
     * @param string $code The code to parse.
     *
     * @return PhpFileModelInterface The created php file model.
     *
     * @throws ParseException If there is an error during parsing.
     */
    public function invoke(string $code): PhpFileModelInterface;
}
