<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConsoleConfig;
use PhpUnitGen\Configuration\YamlConsoleConfigFactory;
use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class YamlConsoleConfigFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Configuration\YamlConsoleConfigFactory
 */
class YamlConsoleConfigFactoryTest extends TestCase
{
    private $configFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->configFactory = new YamlConsoleConfigFactory();
    }

    /**
     * @covers \PhpUnitGen\Configuration\YamlConsoleConfigFactory::decode()
     */
    public function testConfig(): void
    {
        $config = __DIR__ . '/../../../examples/phpunitgen.config.yml';

        $this->assertInstanceOf(ConsoleConfig::class, $this->configFactory->invoke($config));
    }

    /**
     * @covers \PhpUnitGen\Configuration\YamlConsoleConfigFactory::decode()
     */
    public function testInvalidTextConfig(): void
    {
        $config = __DIR__ . '/invalid_yml_config.txt';

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Unable to parse YAML config: Unable to parse at line 1 (near "{").');

        $this->configFactory->invoke($config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\YamlConsoleConfigFactory::decode()
     */
    public function testInvalidYamlConfig(): void
    {
        $config = __DIR__ . '/invalid_config.txt';

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Invalid YAML config');

        $this->configFactory->invoke($config);
    }
}
