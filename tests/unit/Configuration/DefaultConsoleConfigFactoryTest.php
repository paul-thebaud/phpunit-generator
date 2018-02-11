<?php

namespace UnitTests\PhpUnitGen\Configuration;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConsoleConfig;
use PhpUnitGen\Configuration\DefaultConsoleConfigFactory;

/**
 * Class DefaultConsoleConfigFactoryTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Configuration\DefaultConsoleConfigFactory
 */
class DefaultConsoleConfigFactoryTest extends TestCase
{
    /**
     * @var array $array
     */
    private $array = [
        'overwrite' => false,
        'backup'    => false,
        'interface' => false,
        'auto'      => false,
        'ignore'    => true,
        'exclude'   => '/^.*config.php$/',
        'include'   => '/^.*.php$/',
        'dirs'      => [],
        'files'     => [],
        'phpdoc'    => []
    ];

    /**
     * @covers \PhpUnitGen\Configuration\DefaultConsoleConfigFactory::invokeDir()
     */
    public function testInvokeDir(): void
    {
        $configProperty = (new \ReflectionClass(ConsoleConfig::class))
            ->getProperty('config');
        $configProperty->setAccessible(true);

        $factory = new DefaultConsoleConfigFactory();

        $array          = $this->array;
        $array['dirs']  = ['source/dir' => 'target/dir'];
        $array['files'] = [];

        $config = $factory->invokeDir('source/dir', 'target/dir');
        $this->assertSame(
            $array,
            $configProperty->getValue($config)
        );
    }

    /**
     * @covers \PhpUnitGen\Configuration\DefaultConsoleConfigFactory::invokeFile()
     */
    public function testInvokeFile(): void
    {
        $configProperty = (new \ReflectionClass(ConsoleConfig::class))
            ->getProperty('config');
        $configProperty->setAccessible(true);

        $factory = new DefaultConsoleConfigFactory();

        $array          = $this->array;
        $array['files'] = ['source/file.php' => 'target/file.php'];
        $array['dirs']  = [];

        $config = $factory->invokeFile('source/file.php', 'target/file.php');
        $this->assertSame(
            $array,
            $configProperty->getValue($config)
        );
    }
}
