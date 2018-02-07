<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConsoleConfig;
use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class ConsoleConfigTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Configuration\ConsoleConfig
 */
class ConsoleConfigTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validate()
     */
    public function testValidate(): void
    {
        $config = new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
        $this->assertInstanceOf(ConsoleConfig::class, $config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testOverwriteMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"overwrite" parameter must be set as a boolean.');

        new ConsoleConfig([
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testOverwriteInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"overwrite" parameter must be set as a boolean.');

        new ConsoleConfig([
            'overwrite' => 'Invalid parameter',
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testIgnoreMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"ignore" parameter must be set as a boolean.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testIgnoreInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"ignore" parameter must be set as a boolean.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => 'Invalid parameter',
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateIncludeRegex()
     */
    public function testIncludeMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"include" parameter must be set as a string or a null value.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateIncludeRegex()
     */
    public function testIncludeInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"include" parameter must be set as a string or a null value.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => true,
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateExcludeRegex()
     */
    public function testExcludeMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"exclude" parameter must be set as a string or a null value.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateExcludeRegex()
     */
    public function testExcludeInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"exclude" parameter must be set as a string or a null value.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => true,
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateDirs()
     */
    public function testDirsMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"dirs" parameter is not an array.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateDirs()
     */
    public function testDirsInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Some directories in "dirs" parameter are not strings.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [
                'invalid' => true
            ],
            'files'     => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateFiles()
     */
    public function testFilesMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"files" parameter is not an array.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateFiles()
     */
    public function testFilesInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Some files in "files" parameter are not strings.');

        new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [
                'invalid' => true
            ],
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::hasInterfaceParsing()
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::hasAuto()
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::getTemplatesPath()
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::getPhpDoc()
     */
    public function testGetters(): void
    {
        $config = new ConsoleConfig([
            'overwrite' => false,
            'interface' => false,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ]);

        $this->assertSame(false, $config->hasOverwrite());
        $this->assertSame(false, $config->hasIgnore());
        $this->assertSame('/.*config\.php$/', $config->getExcludeRegex());
        $this->assertSame('/.*\.php$/', $config->getIncludeRegex());
        $this->assertSame([], $config->getDirectories());
        $this->assertSame([], $config->getFiles());
    }
}
