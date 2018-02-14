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
    /**
     * @var array $config
     */
    private $config;

    /**
     * @var \ReflectionProperty $configProperty
     */
    private $configProperty;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->configProperty = (new \ReflectionClass(BaseConfig::class))
            ->getProperty('config');
        $this->configProperty->setAccessible(true);

        $this->config = [
            'interface' => false,
            'private'   => true,
            'auto'      => false,
            'phpdoc'    => []
        ];
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::__construct()
     */
    public function testDefaultConfig(): void
    {
        $config = new BaseConfig();

        $this->assertSame([
            'interface' => false,
            'private'   => true,
            'auto'      => false,
            'phpdoc'    => []
        ], $this->configProperty->getValue($config));
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testInvalidStringConfig(): void
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

        unset($this->config['interface']);
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testInterfaceInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"interface" parameter must be set as a boolean.');

        $this->config['interface'] = 'Invalid parameter';
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testAutoMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"auto" parameter must be set as a boolean.');

        unset($this->config['auto']);
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testAutoInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"auto" parameter must be set as a boolean.');

        $this->config['auto'] = 'Invalid parameter';
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testPrivateMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"private" parameter must be set as a boolean.');

        unset($this->config['private']);
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validate()
     */
    public function testPrivateInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"private" parameter must be set as a boolean.');

        $this->config['private'] = 'Invalid parameter';
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validatePhpdoc()
     */
    public function testPhpdocMissingConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('"phpdoc" parameter is not an array.');

        unset($this->config['phpdoc']);
        new BaseConfig($this->config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\BaseConfig::validatePhpdoc()
     */
    public function testPhpdocInvalidConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Some annotation in "phpdoc" parameter are not strings.');

        $this->config['phpdoc'] = ['invalid' => true];
        new BaseConfig($this->config);
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

        $this->assertSame(false, $config->hasInterfaceParsing());
        $this->assertSame(true, $config->hasPrivateParsing());
        $this->assertSame(false, $config->hasAuto());
        $this->assertSame(
            realpath(__DIR__ . '/../../../template'),
            $config->getTemplatesPath()
        );
        $this->assertSame([], $config->getPhpDoc());
    }
}
