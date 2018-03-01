<?php

namespace UnitTests\PhpUnitGen\Container;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Container\ConsoleContainerFactory;
use PhpUnitGen\Container\Container;
use PhpUnitGen\Container\ContainerFactory;
use PhpUnitGen\Container\ContainerInterface\ContainerFactoryInterface;
use PhpUnitGen\Exception\ContainerException;
use PhpUnitGen\Report\Report;
use PhpUnitGen\Report\ReportInterface\ReportInterface;
use UnitTests\PhpUnitGen\Resource\PrivateConstructor;

/**
 * Class ContainerTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Container\Container
 */
class ContainerTest extends TestCase
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->container = new Container();
    }

    /**
     * @covers \PhpUnitGen\Container\Container::setInstance()
     */
    public function testSetInstance(): void
    {
        $report = new Report();

        $this->container->setInstance('report_obj', $report);

        $this->assertSame($report, $this->container->get('report_obj'));
    }

    /**
     * @covers \PhpUnitGen\Container\Container::set()
     */
    public function testSet(): void
    {
        $this->container->set('report_obj', Report::class);
        $this->assertInstanceOf(Report::class, $this->container->get('report_obj'));

        $this->container->set(Report::class);
        $this->assertInstanceOf(Report::class, $this->container->get(Report::class));
    }

    /**
     * @covers \PhpUnitGen\Container\Container::get()
     */
    public function testGet(): void
    {
        $this->container->set('report_obj', Report::class);
        $this->assertInstanceOf(Report::class, $this->container->get('report_obj'));

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Identifier is not a string');
        $this->container->get(true);
    }

    /**
     * @covers \PhpUnitGen\Container\Container::has()
     */
    public function testHas(): void
    {
        $this->container->set('report_obj', Report::class);
        $this->assertTrue($this->container->has('report_obj'));

        $this->assertFalse($this->container->has(true));
    }

    /**
     * @covers \PhpUnitGen\Container\Container::get()
     * @covers \PhpUnitGen\Container\Container::resolveInstance()
     * @covers \PhpUnitGen\Container\Container::resolveAutomaticResolvable()
     */
    public function testResolve(): void
    {
        $report = new Report();

        $this->container->setInstance('report_obj', $report);
        $this->assertInstanceOf(Report::class, $this->container->get('report_obj'));

        $this->container->set(ContainerFactoryInterface::class, ContainerFactory::class);
        $this->assertInstanceOf(ContainerFactory::class, $this->container->get(ContainerFactoryInterface::class));

        $reportAutoCreated = $this->container->get(Report::class);
        $this->assertNotSame($report, $reportAutoCreated);
    }

    /**
     * @covers \PhpUnitGen\Container\Container::autoResolve()
     */
    public function testAutoResolveExisting(): void
    {
        $this->assertInstanceOf(Report::class, $this->container->get(Report::class));
    }

    /**
     * @covers \PhpUnitGen\Container\Container::autoResolve()
     */
    public function testAutoResolveNotExisting(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class "\\My\\UnExisting\\Class" does not exists');

        $this->container->get('\\My\\UnExisting\\Class');
    }

    /**
     * @covers \PhpUnitGen\Container\Container::autoResolve()
     */
    public function testAutoResolveNotInstantiable(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class "PhpUnitGen\\Report\\ReportInterface\\ReportInterface" is not instantiable');

        $this->container->get(ReportInterface::class);
    }

    /**
     * @covers \PhpUnitGen\Container\Container::buildInstance()
     */
    public function testBuildInstanceNoConstructor(): void
    {
        $this->assertInstanceOf(Report::class, $this->container->get(Report::class));
    }

    /**
     * @covers \PhpUnitGen\Container\Container::buildInstance()
     */
    public function testBuildInstanceConstructorScalarParameter(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class "DateTime" constructor has a scalar / callable / array type parameter');

        $this->container->get(\DateTime::class);
    }

    /**
     * @covers \PhpUnitGen\Container\Container::buildInstance()
     */
    public function testBuildInstanceWithConstructorParameters(): void
    {
        $factory = new ContainerFactory();

        $this->container->setInstance(ContainerFactory::class, $factory);

        $consoleFactory = $this->container->get(ConsoleContainerFactory::class);

        $factoryProperty = (new \ReflectionClass(ConsoleContainerFactory::class))
            ->getProperty('containerFactory');
        $factoryProperty->setAccessible(true);

        $this->assertSame($factory, $factoryProperty->getValue($consoleFactory));
    }
}
