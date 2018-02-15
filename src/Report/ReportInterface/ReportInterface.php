<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Report\ReportInterface;

/**
 * Interface ReportInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ReportInterface
{
    /**
     * Increase the number of parsed files.
     */
    public function increaseParsedFileNumber(): void;

    /**
     * Increase the number of parsed directories.
     */
    public function increaseParsedDirectoryNumber(): void;

    /**
     * @return int The number of parsed files.
     */
    public function getParsedFileNumber(): int;

    /**
     * @return int The number of parsed directories.
     */
    public function getParsedDirectoryNumber(): int;
}
