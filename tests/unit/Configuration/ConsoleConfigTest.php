<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
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
     * @var array $config
     */
    private $config;

    protected function setUp(): void
    {
        $this->config = [
            'overwrite' => false,
            'backup'    => false,
            'interface' => false,
            'private'   => true,
            'auto'      => false,
            'ignore'    => false,
            'exclude'   => '/.*config\.php$/',
            'include'   => '/.*\.php$/',
            'dirs'      => [],
            'files'     => [],
            'phpdoc'    => []
        ];
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validate()
     */
    public function testValidate(): void
    {
        $config = new ConsoleConfig($this->config);
        $this->assertInstanceOf(ConsoleConfigInterface::class, $config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testOverwriteMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"overwrite" parameter must be set as a boolean.');

        unset($this->config['overwrite']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testOverwriteInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"overwrite" parameter must be set as a boolean.');

        $this->config['overwrite'] = 'Invalid parameter';
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testBackupMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"backup" parameter must be set as a boolean.');

        unset($this->config['backup']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testBackupInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"backup" parameter must be set as a boolean.');

        $this->config['backup'] = 'Invalid parameter';
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testIgnoreMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"ignore" parameter must be set as a boolean.');

        unset($this->config['ignore']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateBooleans()
     */
    public function testIgnoreInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"ignore" parameter must be set as a boolean.');

        $this->config['ignore'] = 'Invalid parameter';
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateIncludeRegex()
     */
    public function testIncludeMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"include" parameter must be set as a string or a null value.');

        unset($this->config['include']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateIncludeRegex()
     */
    public function testIncludeInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"include" parameter must be set as a string or a null value.');

        $this->config['include'] = ['invalid' => true];
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateExcludeRegex()
     */
    public function testExcludeMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"exclude" parameter must be set as a string or a null value.');

        unset($this->config['exclude']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateExcludeRegex()
     */
    public function testExcludeInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"exclude" parameter must be set as a string or a null value.');

        $this->config['exclude'] = ['invalid' => true];
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateDirs()
     */
    public function testDirsMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"dirs" parameter is not an array.');

        unset($this->config['dirs']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateDirs()
     */
    public function testDirsNullConfig(): void
    {
        $this->config['dirs'] = null;
        $config = new ConsoleConfig($this->config);

        $this->assertSame([], $config->getDirectories());
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateDirs()
     */
    public function testDirsInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Some directories in "dirs" parameter are not strings.');

        $this->config['dirs'] = ['invalid' => true];
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateFiles()
     */
    public function testFilesMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"files" parameter is not an array.');

        unset($this->config['files']);
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateFiles()
     */
    public function testFilesNullConfig(): void
    {
        $this->config['files'] = null;
        $config = new ConsoleConfig($this->config);

        $this->assertSame([], $config->getFiles());
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::validateFiles()
     */
    public function testFilesInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Some files in "files" parameter are not strings.');

        $this->config['files'] = ['invalid' => true];
        new ConsoleConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::hasInterfaceParsing()
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::hasAuto()
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::getTemplatesPath()
     * @covers \PhpUnitGen\Configuration\ConsoleConfig::getPhpDoc()
     */
    public function testGetters(): void
    {
        $config = new ConsoleConfig($this->config);

        $this->assertSame(false, $config->hasOverwrite());
        $this->assertSame(false, $config->hasBackup());
        $this->assertSame(false, $config->hasIgnore());
        $this->assertSame('/.*config\.php$/', $config->getExcludeRegex());
        $this->assertSame('/.*\.php$/', $config->getIncludeRegex());
        $this->assertSame([], $config->getDirectories());
        $this->assertSame([], $config->getFiles());
    }
}
