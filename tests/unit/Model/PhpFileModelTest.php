<?php

namespace UnitTests\PhpUnitGen\Model;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\AssertAnnotation;
use PhpUnitGen\Annotation\MockAnnotation;
use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\InterfaceModel;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Model\TraitModel;

/**
 * Class PhpFileModelTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PhpFileModel
 */
class PhpFileModelTest extends TestCase
{
    /**
     * @var PhpFileModel $phpFile
     */
    private $phpFile;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->phpFile = new PhpFileModel();
    }

    /**
     * @covers \PhpUnitGen\Model\PhpFileModel::addConcreteUse()
     * @covers \PhpUnitGen\Model\PhpFileModel::getConcreteUses()
     * @covers \PhpUnitGen\Model\PhpFileModel::addUse()
     * @covers \PhpUnitGen\Model\PhpFileModel::hasUse()
     * @covers \PhpUnitGen\Model\PhpFileModel::getUse()
     */
    public function testUsesMethods(): void
    {
        $this->assertFalse($this->phpFile->hasUse('MyClass'));

        $this->phpFile->addUse('MyClass', 'MyNamespace\MyClass');
        $this->assertTrue($this->phpFile->hasUse('MyClass'));
        $this->assertSame('MyNamespace\MyClass', $this->phpFile->getUse('MyClass'));

        $this->assertCount(0, $this->phpFile->getConcreteUses());

        // Not contained with an alias
        $this->phpFile->addConcreteUse('\DateTime', 'Date');
        $this->assertSame([
            'DateTime' => 'Date'
        ], $this->phpFile->getConcreteUses());

        // Already contained
        $this->phpFile->addConcreteUse('\DateTime', 'Date');
        $this->assertSame([
            'DateTime' => 'Date'
        ], $this->phpFile->getConcreteUses());

        // Same name
        $this->phpFile->addConcreteUse('MyNamespace\CustomDate', 'Date');
        $this->assertSame([
            'DateTime'               => 'Date',
            'MyNamespace\CustomDate' => 'DateAlias'
        ], $this->phpFile->getConcreteUses());

        // Alias exists
        $this->phpFile->addUse('Employee', 'Another\Company\Employee');
        $this->phpFile->addUse('MyEmployee', 'My\Company\Employee');
        $this->phpFile->addConcreteUse('Another\Company\Employee', 'Employee');
        $this->phpFile->addConcreteUse('My\Company\Employee', 'Employee');
        $this->assertSame([
            'DateTime'                 => 'Date',
            'MyNamespace\CustomDate'   => 'DateAlias',
            'Another\Company\Employee' => 'Employee',
            'My\Company\Employee'      => 'MyEmployee'
        ], $this->phpFile->getConcreteUses());
    }

    /**
     * @covers \PhpUnitGen\Model\PhpFileModel::getUse()
     */
    public function testGetUseThrowException(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Trying to get a full class name for "Date", but it does not exists');

        $this->phpFile->getUse('Date');
    }

    /**
     * @covers \PhpUnitGen\Model\PhpFileModel::getFullNameFor()
     */
    public function testNamespaceMethods(): void
    {
        $this->assertSame('MyClass', $this->phpFile->getFullNameFor('MyClass'));

        $this->phpFile->setNamespace(['My\CurrentNamespace']);

        $this->assertSame('My\CurrentNamespace\MyClass', $this->phpFile->getFullNameFor('MyClass'));
    }

    /**
     * @covers \PhpUnitGen\Model\PhpFileModel::addClass()
     * @covers \PhpUnitGen\Model\PhpFileModel::getClasses()
     * @covers \PhpUnitGen\Model\PhpFileModel::addTrait()
     * @covers \PhpUnitGen\Model\PhpFileModel::getTraits()
     * @covers \PhpUnitGen\Model\PhpFileModel::addInterface()
     * @covers \PhpUnitGen\Model\PhpFileModel::getInterfaces()
     * @covers \PhpUnitGen\Model\PhpFileModel::getClassLikeCollection()
     */
    public function testComponentsMethods(): void
    {
        $this->assertSame(0, $this->phpFile->getClasses()->count());
        $this->assertSame(0, $this->phpFile->getTraits()->count());
        $this->assertSame(0, $this->phpFile->getInterfaces()->count());
        $this->assertSame(0, $this->phpFile->getClassLikeCollection()->count());

        $class1 = new ClassModel();
        $class2 = new ClassModel();
        $this->phpFile->addClass($class1);
        $this->phpFile->addClass($class2);
        $this->assertSame(2, $this->phpFile->getClasses()->count());
        $this->assertSame($class1, $this->phpFile->getClasses()->first());
        $this->assertSame($class2, $this->phpFile->getClasses()->get(1));

        $trait1 = new TraitModel();
        $trait2 = new TraitModel();
        $this->phpFile->addTrait($trait1);
        $this->phpFile->addTrait($trait2);
        $this->assertSame(2, $this->phpFile->getTraits()->count());
        $this->assertSame($trait1, $this->phpFile->getTraits()->first());
        $this->assertSame($trait2, $this->phpFile->getTraits()->get(1));

        $interface1 = new InterfaceModel();
        $interface2 = new InterfaceModel();
        $this->phpFile->addInterface($interface1);
        $this->phpFile->addInterface($interface2);
        $this->assertSame(2, $this->phpFile->getInterfaces()->count());
        $this->assertSame($interface1, $this->phpFile->getInterfaces()->first());
        $this->assertSame($interface2, $this->phpFile->getInterfaces()->get(1));

        $this->assertSame($class1, $this->phpFile->getClassLikeCollection()->get(0));
        $this->assertSame($class2, $this->phpFile->getClassLikeCollection()->get(1));
        $this->assertSame($trait1, $this->phpFile->getClassLikeCollection()->get(2));
        $this->assertSame($trait2, $this->phpFile->getClassLikeCollection()->get(3));
        $this->assertSame($interface1, $this->phpFile->getClassLikeCollection()->get(4));
        $this->assertSame($interface2, $this->phpFile->getClassLikeCollection()->get(5));
    }

    /**
     * @covers \PhpUnitGen\Model\PhpFileModel::getTestableFunctionsCount()
     * @covers \PhpUnitGen\Model\PhpFileModel::getInterfacesFunctionsCount()
     */
    public function testCountMethods(): void
    {
        $this->assertSame(0, $this->phpFile->getTestableFunctionsCount());
        $this->assertSame(0, $this->phpFile->getInterfacesFunctionsCount());

        $this->phpFile->addFunction(new FunctionModel());
        $this->phpFile->addFunction(new FunctionModel());
        $this->phpFile->addFunction(new FunctionModel());

        $class1 = $this->createMock(ClassModel::class);
        $class1->expects($this->once())->method('countNotAbstractFunctions')
            ->with()->willReturn(12);
        $this->phpFile->addClass($class1);
        $class2 = $this->createMock(ClassModel::class);
        $class2->expects($this->once())->method('countNotAbstractFunctions')
            ->with()->willReturn(5);
        $this->phpFile->addClass($class2);
        $trait1 = $this->createMock(TraitModel::class);
        $trait1->expects($this->once())->method('countNotAbstractFunctions')
            ->with()->willReturn(7);
        $this->phpFile->addTrait($trait1);
        $trait2 = $this->createMock(TraitModel::class);
        $trait2->expects($this->once())->method('countNotAbstractFunctions')
            ->with()->willReturn(8);
        $this->phpFile->addTrait($trait2);

        $this->assertSame(35, $this->phpFile->getTestableFunctionsCount());

        $interface1 = $this->createMock(InterfaceModel::class);
        $interface1->expects($this->once())->method('countNotAbstractFunctions')
            ->with()->willReturn(2);
        $this->phpFile->addInterface($interface1);
        $interface2 = $this->createMock(InterfaceModel::class);
        $interface2->expects($this->once())->method('countNotAbstractFunctions')
            ->with()->willReturn(4);
        $this->phpFile->addInterface($interface2);

        $this->assertSame(6, $this->phpFile->getInterfacesFunctionsCount());
    }

    /**
     * @covers \PhpUnitGen\Model\PhpFileModel::getMockAnnotations()
     */
    public function testAnnotationMethods(): void
    {
        $this->assertSame(0, $this->phpFile->getMockAnnotations()->count());

        $annotation1 = new MockAnnotation();
        $annotation2 = new AssertAnnotation();
        $annotation3 = new MockAnnotation();
        $this->phpFile->addAnnotation($annotation1);
        $this->phpFile->addAnnotation($annotation2);
        $this->phpFile->addAnnotation($annotation3);

        $this->assertSame(2, $this->phpFile->getMockAnnotations()->count());
        $this->assertSame($annotation1, $this->phpFile->getMockAnnotations()->first());
        $this->assertSame($annotation3, $this->phpFile->getMockAnnotations()->get(2));
    }
}
