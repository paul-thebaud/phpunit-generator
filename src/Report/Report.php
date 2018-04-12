<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Report;

use PhpUnitGen\Report\ReportInterface\ReportInterface;

/**
 * Class Report.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class Report implements ReportInterface
{
    /**
     * @var int $parsedFilesNumber The number of parsed files.
     */
    private $parsedFilesNumber = 0;

    /**
     * @var int $parsedDirectoriesNumber The number of parsed directories.
     */
    private $parsedDirectoriesNumber = 0;

    /**
     * @var int $ignoredErrorsNumber The number of errors that occurred during process.
     */
    private $ignoredErrorsNumber = 0;

    /**
     * {@inheritdoc}
     */
    public function increaseParsedFileNumber(): void
    {
        $this->parsedFilesNumber++;
    }

    /**
     * {@inheritdoc}
     */
    public function increaseParsedDirectoryNumber(): void
    {
        $this->parsedDirectoriesNumber++;
    }

    /**
     * {@inheritdoc}
     */
    public function increaseIgnoredErrorNumber(): void
    {
        $this->ignoredErrorsNumber++;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedFileNumber(): int
    {
        return $this->parsedFilesNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedDirectoryNumber(): int
    {
        return $this->parsedDirectoriesNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getIgnoredErrorNumber(): int
    {
        return $this->ignoredErrorsNumber;
    }
}
