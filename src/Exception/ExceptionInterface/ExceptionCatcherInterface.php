<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Exception\ExceptionInterface;

use PhpUnitGen\Exception\Exception;

/**
 * Interface ExceptionCatcherInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ExceptionCatcherInterface
{
    /**
     * Catch an exception, and display a warning or throw an exception depending on configuration.
     *
     * @param Exception $exception The exception to catch.
     * @param string    $path      The concerned file path.
     *
     * @throws Exception If the configuration does not allow ignoring errors.
     */
    public function catch(Exception $exception, string $path): void;
}
