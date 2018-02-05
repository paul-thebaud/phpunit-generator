<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\BaseConfig;
use PhpUnitGen\Configuration\ConsoleConfig;
use PhpUnitGen\Configuration\PhpConsoleConfigFactory;
use PhpUnitGen\Exception\InvalidConfigException;

/**
 * Class PhpConsoleConfigFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Configuration\PhpConsoleConfigFactory
 */
class PhpConsoleConfigFactoryTest extends TestCase
{
    private $configFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->configFactory = new PhpConsoleConfigFactory();
    }

    /**
     * @covers \PhpUnitGen\Configuration\PhpConsoleConfigFactory::decode()
     */
    public function testConfig(): void
    {
        $config = __DIR__ . '/../../../examples/phpunitgen.config.php';

        $this->assertInstanceOf(ConsoleConfig::class, $this->configFactory->invoke($config));
    }

    /**
     * @covers \PhpUnitGen\Configuration\PhpConsoleConfigFactory::decode()
     */
    public function testInvalidConfig(): void
    {
        $config = __DIR__ . '/invalid_php_config.php';

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Invalid PHP config');

        $this->configFactory->invoke($config);
    }
}
