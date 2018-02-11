<?php

namespace UnitTests\PhpUnitGen\Report;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Report\Report;

/**
 * Class ReportTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers \PhpUnitGen\Report\Report
 */
class ReportTest extends TestCase
{
    /**
     * @var Report $report
     */
    private $report;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->report = new Report();
    }

    /**
     * @covers \PhpUnitGen\Report\Report::increaseParsedFileNumber()
     * @covers \PhpUnitGen\Report\Report::getParsedFileNumber()
     */
    public function testParsedFileNumber(): void
    {
        $this->assertSame(0, $this->report->getParsedFileNumber());
        $this->report->increaseParsedFileNumber();
        $this->assertSame(1, $this->report->getParsedFileNumber());
        $this->report->increaseParsedFileNumber();
        $this->assertSame(2, $this->report->getParsedFileNumber());
    }

    /**
     * @covers \PhpUnitGen\Report\Report::increaseParsedDirectoryNumber()
     * @covers \PhpUnitGen\Report\Report::getParsedDirectoryNumber()
     */
    public function testParsedDirectoryNumber(): void
    {
        $this->assertSame(0, $this->report->getParsedDirectoryNumber());
        $this->report->increaseParsedDirectoryNumber();
        $this->assertSame(1, $this->report->getParsedDirectoryNumber());
        $this->report->increaseParsedDirectoryNumber();
        $this->assertSame(2, $this->report->getParsedDirectoryNumber());
    }
}
