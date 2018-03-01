<?php

namespace FunctionalTests\PhpUnitGen;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\BaseConfig;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Container\ContainerFactory;
use PhpUnitGen\Executor\ExecutorInterface\ExecutorInterface;

/**
 * Class FunctionalTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FunctionalTest extends TestCase
{
    /**
     * Get the PhpUnitGen tasks executor for a configuration or with the default configuration.
     *
     * @param ConfigInterface|null $config The configuration, null if default configuration is needed.
     *
     * @return ExecutorInterface The PhpUnitGen tasks executor.
     */
    private function getExecutor(ConfigInterface $config = null): ExecutorInterface
    {
        $containerFactory = new ContainerFactory();
        if ($config !== null) {
            return ($containerFactory->invoke($config))->get(ExecutorInterface::class);
        }
        return ($containerFactory->invoke(new BaseConfig()))->get(ExecutorInterface::class);
    }

    public function testMultipleDeclaration(): void
    {
        $this->assertSame(
            file_get_contents(__DIR__ . '/resource/multiple_declaration_result.php'),
            $this->getExecutor()->invoke(file_get_contents(__DIR__ . '/resource/multiple_declaration.php'), 'multiple_declaration_result')
        );
    }

    public function testMultipleDeclarationWithAnnotation(): void
    {
        $this->assertSame(
            file_get_contents(__DIR__ . '/resource/multiple_declaration_with_annotations_result.php'),
            $this->getExecutor()->invoke(file_get_contents(__DIR__ . '/resource/multiple_declaration_with_annotations.php'), 'multiple_declaration_with_annotations_result')
        );
    }

    public function testAutoDetectAndInterfaces(): void
    {
        $config = new BaseConfig(require __DIR__ . '/resource/config.php');
        $this->assertSame(
            file_get_contents(__DIR__ . '/resource/auto_detect_result.php'),
            $this->getExecutor($config)->invoke(file_get_contents(__DIR__ . '/resource/auto_detect.php'), 'auto_detect_result')
        );
    }
}
