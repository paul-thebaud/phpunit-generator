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
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * Build the instance of TwigTestRenderer
     */
    protected function setUp()
    {
        $this->config = $this->createMock(ConfigInterface::class);

        $this->instance = new TwigTestRenderer($this->config);
    }

    /**
     * @covers \PHPUnitGenerator\Renderer\TwigTestRenderer::__construct()
     */
    public function testConstruct()
    {
        $propertyTwig = (new \ReflectionClass(TwigTestRenderer::class))->getProperty('twig');
        $propertyTwig->setAccessible(true);
        $propertyConfig = (new \ReflectionClass(TwigTestRenderer::class))->getProperty('config');
        $propertyConfig->setAccessible(true);

        $mock = $this->getMockBuilder(TwigTestRenderer::class)
            ->setMethods(['getFilesystemLoader'])
            ->setConstructorArgs([$this->config])
            ->getMock();
        $mock->expects($this->exactly(2))->method('getFilesystemLoader')
            ->withConsecutive([TwigTestRenderer::DEFAULT_TEMPLATE_FOLDER], ['/custom/dir'])
            ->willReturn($this->createMock(FilesystemLoader::class));

        $this->config = new Config([]);

        // Without a custom configuration
        $mock->__construct($this->config);
        $this->assertEquals($this->config, $propertyConfig->getValue($mock));
        $twig = $propertyTwig->getValue($mock);
        $this->assertInstanceOf(Environment::class, $twig);
        $this->assertFalse($twig->hasExtension(\Twig_Extension_Debug::class));
        $this->assertFalse($twig->getCache());
        $this->assertNotNull($twig->getFilter('lcfirst'));

        $this->config = new Config([
            ConfigInterface::OPTION_TWIG_TEMPLATE_FOLDER => '/custom/dir',
            ConfigInterface::OPTION_TWIG_DEBUG           => true,
        ]);

        // With a custom configuration
        $mock->__construct($this->config);
        $this->assertEquals($this->config, $propertyConfig->getValue($mock));
        $twig = $propertyTwig->getValue($mock);
        $this->assertInstanceOf(Environment::class, $twig);
        $this->assertTrue($twig->hasExtension(\Twig_Extension_Debug::class));
        $this->assertFalse($twig->getCache());
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
}
