<?php

namespace Test\PHPUnitGenerator\Renderer\TwigTestRenderer;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Config\Config;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Renderer\TwigTestRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigTestRendererTest
 *
 * @covers \PHPUnitGenerator\Renderer\TwigTestRenderer
 */
class TwigTestRendererTest extends TestCase
{
    /**
     * @var TwigTestRenderer $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of TwigTestRenderer
     */
    protected function setUp()
    {
        $this->instance = new TwigTestRenderer();
    }

    /**
     * @covers \PHPUnitGenerator\Renderer\TwigTestRenderer::__construct()
     */
    public function testConstruct()
    {
        $propertyTwig = (new \ReflectionClass(TwigTestRenderer::class))->getProperty('twig');
        $propertyTwig->setAccessible(true);

        $twig = $propertyTwig->getValue($this->instance);
        $this->assertInstanceOf(Environment::class, $twig);
        $this->assertFalse($twig->isDebug());
        $this->assertFalse($twig->getCache());
        $this->assertEquals(TwigTestRenderer::DEFAULT_TEMPLATE_FOLDER, $twig->getLoader()->getPaths()[0]);
        $this->assertNotNull($twig->getFilter('lcfirst'));
    }

    /**
     * @covers \PHPUnitGenerator\Renderer\TwigTestRenderer::render()
     */
    public function testRender()
    {
        $classModel = $this->createMock(ClassModelInterface::class);
        $twig = $this->createMock(Environment::class);

        $property = (new \ReflectionClass(TwigTestRenderer::class))->getProperty('twig');
        $property->setAccessible(true);
        $property->setValue($this->instance, $twig);

        $twig->expects($this->once())->method('render')
            ->with('class.twig', ['class' => $classModel])
            ->willReturn('render');

        $this->assertEquals('render', $this->instance->render($classModel));
    }

    /**
     * @covers \PHPUnitGenerator\Renderer\TwigTestRenderer::getFilesystemLoader()
     */
    public function testGetFilesystemLoader()
    {
        $method = (new \ReflectionClass(TwigTestRenderer::class))->getMethod('getFilesystemLoader');
        $method->setAccessible(true);

        $loader = $method->invoke($this->instance, TwigTestRenderer::DEFAULT_TEMPLATE_FOLDER);
        $this->assertEquals(TwigTestRenderer::DEFAULT_TEMPLATE_FOLDER, $loader->getPaths()[0]);
    }
}
