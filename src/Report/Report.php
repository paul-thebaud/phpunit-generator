<?php

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
     * @var int $parsedFilesFromDirectoriesNumber The number of parsed files from directories.
     */
    private $parsedFilesFromDirectoriesNumber = 0;

    /**
     * @var int $parsedDirectoriesNumber The number of parsed directories.
     */
    private $parsedDirectoriesNumber = 0;

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
    public function increaseParsedFileFromDirectoryNumber(): void
    {
        $this->parsedFilesFromDirectoriesNumber++;
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
    public function getParsedFileNumber(): int
    {
        return $this->parsedFilesNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedFileFromDirectoryNumber(): int
    {
        return $this->parsedFilesFromDirectoriesNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedDirectoryNumber(): int
    {
        return $this->parsedDirectoriesNumber;
    }
}
