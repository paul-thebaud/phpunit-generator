<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\BaseConfig;
use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class BaseConfigTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Configuration\BaseConfig
 */
class BaseConfigTest extends TestCase
{
    private $configProperty;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->configProperty = (new \ReflectionClass(BaseConfig::class))
            ->getProperty('config');
        $this->configProperty->setAccessible(true);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::__construct()
     */
    public function testDefaultConfig(): void
    {
        $config = new BaseConfig();

        $this->assertEquals([
            'interface' => false,
            'auto'      => false,
            'phpdoc'    => []
        ], $this->configProperty->getValue($config));
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testStringConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('The config must be an array.');

        new BaseConfig('Invalid configuration');
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testInterfaceMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"interface" parameter must be set as a boolean.');

        new BaseConfig([
            'auto'   => false,
            'phpdoc' => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testInterfaceInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"interface" parameter must be set as a boolean.');

        new BaseConfig([
            'interface' => 'Invalid parameter',
            'auto'      => false,
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testAutoMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"auto" parameter must be set as a boolean.');

        new BaseConfig([
            'interface' => false,
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testAutoInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"auto" parameter must be set as a boolean.');

        new BaseConfig([
            'interface' => false,
            'auto'      => 'Invalid parameter',
            'phpdoc'    => []
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validatePhpdoc()
     */
    public function testPhpdocInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"phpdoc" parameter is not an array.');

        new BaseConfig([
            'interface' => false,
            'auto'      => false
        ]);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::hasInterfaceParsing()
     * @covers \PhpUnitGen\Configuration\BaseConfig::hasAuto()
     * @covers \PhpUnitGen\Configuration\BaseConfig::getTemplatesPath()
     * @covers \PhpUnitGen\Configuration\BaseConfig::getPhpDoc()
     */
    public function testGetters(): void
    {
        $config = new BaseConfig();

        $this->assertEquals(false, $config->hasInterfaceParsing());
        $this->assertEquals(false, $config->hasAuto());
        $this->assertEquals(
            realpath(__DIR__ . '/../../../template'),
            $config->getTemplatesPath()
        );
        $this->assertEquals([], $config->getPhpDoc());
    }
}
