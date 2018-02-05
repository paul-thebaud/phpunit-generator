<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\BaseConfig;
use PhpUnitGen\Configuration\ConsoleConfig;
use PhpUnitGen\Configuration\JsonConsoleConfigFactory;
use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class JsonConsoleConfigFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Configuration\JsonConsoleConfigFactory
 */
class JsonConsoleConfigFactoryTest extends TestCase
{
    private $configFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->configFactory = new JsonConsoleConfigFactory();
    }

    /**
     * @covers \PhpUnitGen\Configuration\JsonConsoleConfigFactory::decode()
     */
    public function testConfig(): void
    {
        $config = __DIR__ . '/../../../examples/phpunitgen.config.json';

        $this->assertInstanceOf(ConsoleConfig::class, $this->configFactory->invoke($config));
    }

    /**
     * @covers \PhpUnitGen\Configuration\JsonConsoleConfigFactory::decode()
     */
    public function testInvalidConfig(): void
    {
        $config = __DIR__ . '/invalid_config.txt';

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Unable to parse JSON config');

        $this->configFactory->invoke($config);
    }

    /**
     * @covers \PhpUnitGen\Configuration\JsonConsoleConfigFactory::decode()
     */
    public function testInvalidTextConfig(): void
    {
        $config = __DIR__ . '/invalid_json_config.txt';

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Invalid JSON config');

        $this->configFactory->invoke($config);
    }
}
