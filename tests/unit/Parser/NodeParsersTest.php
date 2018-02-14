<?php

namespace UnitTests\PhpUnitGen\Parser;

use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\BaseConfig;
use PhpUnitGen\Container\Container;
use PhpUnitGen\Container\ContainerFactory;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Model\PropertyInterface\TypeInterface;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;
use PhpUnitGen\Model\ReturnModel;
use PhpUnitGen\Parser\NodeParser\AbstractNodeParser;
use PhpUnitGen\Parser\NodeParser\AttributeNodeParser;
use PhpUnitGen\Parser\NodeParser\ClassNodeParser;
use PhpUnitGen\Parser\NodeParser\DocumentationNodeParser;
use PhpUnitGen\Parser\NodeParser\FunctionNodeParser;
use PhpUnitGen\Parser\NodeParser\GroupUseNodeParser;
use PhpUnitGen\Parser\NodeParser\InterfaceNodeParser;
use PhpUnitGen\Parser\NodeParser\MethodNodeParser;
use PhpUnitGen\Parser\NodeParser\NamespaceNodeParser;
use PhpUnitGen\Parser\NodeParser\ParameterNodeParser;
use PhpUnitGen\Parser\NodeParser\PhpFileNodeParser;
use PhpUnitGen\Parser\NodeParser\TraitNodeParser;
use PhpUnitGen\Parser\NodeParser\TypeNodeParser;
use PhpUnitGen\Parser\NodeParser\UseNodeParser;
use PhpUnitGen\Parser\NodeParser\ValueNodeParser;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;

/**
 * Class NodeParsersTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\NodeParser\AbstractFunctionNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\AbstractNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\AttributeNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\ClassNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\DocumentationNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\FunctionNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\GroupUseNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\InterfaceNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\MethodNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\NamespaceNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\ParameterNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\PhpFileNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\TraitNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\TypeNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\UseNodeParser
 * @covers     \PhpUnitGen\Parser\NodeParser\ValueNodeParser
 */
class NodeParsersTest extends TestCase
{
    /**
     * @var Container $container
     */
    private static $container;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass(): void
    {
        self::$container = (new ContainerFactory())->invoke(new BaseConfig());
    }

    public function testNodeParsersWithInterfacesAndAuto(): void
    {
        $config = new BaseConfig(['auto' => true, 'interface' => true, 'phpdoc' => []]);
        $container = (new ContainerFactory())->invoke($config);

        $code = file_get_contents(__DIR__ . '/resource/with_namespace.php');

        /** @var PhpFileModelInterface $phpFileModel */
        $phpFileModel = $container->get(PhpParserInterface::class)->invoke($code);

        // Check main properties
        $this->assertSame('UNKNOWN_NAME', $phpFileModel->getName());
        $this->assertSame(['AnotherNamespace', 'Current'], $phpFileModel->getNamespace());
        $this->assertNull($phpFileModel->getParentNode());

        // Check uses
        $usesProperty = (new \ReflectionClass($phpFileModel))->getProperty('uses');
        $usesProperty->setAccessible(true);
        $this->assertCount(6, $usesProperty->getValue($phpFileModel));
        $this->assertSame('MyNamespace\\A', $phpFileModel->getUse('A'));
        $this->assertSame('MyNamespace\\B', $phpFileModel->getUse('Another'));
        $this->assertSame('MyNamespace\\AnotherNamespace\\C', $phpFileModel->getUse('C'));
        $this->assertSame('MyNamespace\\AnotherNamespace\\D', $phpFileModel->getUse('D'));
        $this->assertSame('E', $phpFileModel->getUse('E'));
        $this->assertSame('MyNamespace\\F', $phpFileModel->getUse('F'));

        // Check for used class
        $concreteUses = $phpFileModel->getConcreteUses();
        $this->assertCount(15, $concreteUses);
        $this->assertArrayHasKey('MyNamespace\\AnotherNamespace\\D', $concreteUses);
        $this->assertSame('D', $concreteUses['MyNamespace\\AnotherNamespace\\D']);
        $this->assertArrayHasKey('MyNamespace\\B', $concreteUses);
        $this->assertSame('B', $concreteUses['MyNamespace\\B']);
        $this->assertArrayHasKey('DateTime', $concreteUses);
        $this->assertSame('DateTime', $concreteUses['DateTime']);
        $this->assertArrayHasKey('MyNamespace\\A', $concreteUses);
        $this->assertSame('A', $concreteUses['MyNamespace\\A']);
        $this->assertArrayHasKey('E', $concreteUses);
        $this->assertSame('E', $concreteUses['E']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\G', $concreteUses);
        $this->assertSame('G', $concreteUses['AnotherNamespace\\Current\\G']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\MyOtherClass', $concreteUses);
        $this->assertSame('MyOtherClass', $concreteUses['AnotherNamespace\\Current\\MyOtherClass']);
        $this->assertArrayHasKey('MyNamespace\\A\\MySub', $concreteUses);
        $this->assertSame('MySub', $concreteUses['MyNamespace\\A\\MySub']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\H\\SomeClass', $concreteUses);
        $this->assertSame('SomeClass', $concreteUses['AnotherNamespace\\Current\\H\\SomeClass']);
        $this->assertArrayHasKey('MyNamespace\\F', $concreteUses);
        $this->assertSame('F', $concreteUses['MyNamespace\\F']);

        // Check for class in file
        $this->assertArrayHasKey('AnotherNamespace\\Current\\AFinalClassWithGetter', $concreteUses);
        $this->assertSame('AFinalClassWithGetter', $concreteUses['AnotherNamespace\\Current\\AFinalClassWithGetter']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\AClass', $concreteUses);
        $this->assertSame('AClass', $concreteUses['AnotherNamespace\\Current\\AClass']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\AAbstractClass', $concreteUses);
        $this->assertSame('AAbstractClass', $concreteUses['AnotherNamespace\\Current\\AAbstractClass']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\ATrait', $concreteUses);
        $this->assertSame('ATrait', $concreteUses['AnotherNamespace\\Current\\ATrait']);
        $this->assertArrayHasKey('AnotherNamespace\\Current\\AInterface', $concreteUses);
        $this->assertSame('AInterface', $concreteUses['AnotherNamespace\\Current\\AInterface']);

        // Check globals functions and arguments / return parsing
        $this->assertSame(4, $phpFileModel->getFunctions()->count());

        $globalFunc1 = $phpFileModel->getFunction('globalFunc1');
        $this->assertSame($phpFileModel, $globalFunc1->getParentNode());
        $this->assertSame("/**\n * Some documentation...\n */", $globalFunc1->getDocumentation());
        $this->assertNull($globalFunc1->getVisibility());
        $this->assertTrue($globalFunc1->isGlobal());
        $this->assertFalse($globalFunc1->isAbstract());
        $this->assertFalse($globalFunc1->isFinal());
        $this->assertFalse($globalFunc1->isStatic());
        $this->assertSame($globalFunc1, $globalFunc1->getReturn()->getParentNode());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc1->getReturn()->getType());
        $this->assertSame('B', $globalFunc1->getReturn()->getCustomType());
        $this->assertTrue($globalFunc1->getReturn()->nullable());
        $globalFunc1Parameters = $globalFunc1->getParameters();
        $this->assertSame(3, $globalFunc1Parameters->count());
        $this->assertSame($globalFunc1, $globalFunc1Parameters[0]->getParentNode());
        $this->assertSame('argWithoutAnything', $globalFunc1Parameters[0]->getName());
        $this->assertSame(TypeInterface::MIXED, $globalFunc1Parameters[0]->getType());
        $this->assertFalse($globalFunc1Parameters[0]->nullable());
        $this->assertFalse($globalFunc1Parameters[0]->isVariadic());
        $this->assertSame($globalFunc1, $globalFunc1Parameters[1]->getParentNode());
        $this->assertSame('argInt', $globalFunc1Parameters[1]->getName());
        $this->assertSame(TypeInterface::INT, $globalFunc1Parameters[1]->getType());
        $this->assertFalse($globalFunc1Parameters[1]->nullable());
        $this->assertFalse($globalFunc1Parameters[1]->isVariadic());
        $this->assertSame($globalFunc1, $globalFunc1Parameters[2]->getParentNode());
        $this->assertSame('argClassD', $globalFunc1Parameters[2]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc1Parameters[2]->getType());
        $this->assertSame('D', $globalFunc1Parameters[2]->getCustomType());
        $this->assertTrue($globalFunc1Parameters[2]->nullable());
        $this->assertFalse($globalFunc1Parameters[2]->isVariadic());

        $globalFunc2 = $phpFileModel->getFunction('globalFunc2');
        $this->assertSame($phpFileModel, $globalFunc2->getParentNode());
        $this->assertNull($globalFunc2->getDocumentation());
        $this->assertNull($globalFunc2->getVisibility());
        $this->assertTrue($globalFunc2->isGlobal());
        $this->assertFalse($globalFunc2->isAbstract());
        $this->assertFalse($globalFunc2->isFinal());
        $this->assertFalse($globalFunc2->isStatic());
        $this->assertSame($globalFunc2, $globalFunc2->getReturn()->getParentNode());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc2->getReturn()->getType());
        $this->assertSame('E', $globalFunc2->getReturn()->getCustomType());
        $this->assertFalse($globalFunc2->getReturn()->nullable());
        $globalFunc2Parameters = $globalFunc2->getParameters();
        $this->assertSame(3, $globalFunc2Parameters->count());
        $this->assertSame($globalFunc2, $globalFunc2Parameters[0]->getParentNode());
        $this->assertSame('argDefaultValue', $globalFunc2Parameters[0]->getName());
        $this->assertSame(TypeInterface::MIXED, $globalFunc2Parameters[0]->getType());
        $this->assertNull($globalFunc2Parameters[0]->getValue());
        $this->assertFalse($globalFunc2Parameters[0]->nullable());
        $this->assertFalse($globalFunc2Parameters[0]->isVariadic());
        $this->assertSame($globalFunc2, $globalFunc2Parameters[1]->getParentNode());
        $this->assertSame('argFullyQualified', $globalFunc2Parameters[1]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc2Parameters[1]->getType());
        $this->assertSame('DateTime', $globalFunc2Parameters[1]->getCustomType());
        $this->assertFalse($globalFunc2Parameters[1]->nullable());
        $this->assertFalse($globalFunc2Parameters[1]->isVariadic());
        $this->assertSame($globalFunc2, $globalFunc2Parameters[2]->getParentNode());
        $this->assertSame('argClassA', $globalFunc2Parameters[2]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc2Parameters[2]->getType());
        $this->assertSame('A', $globalFunc2Parameters[2]->getCustomType());
        $this->assertFalse($globalFunc2Parameters[2]->nullable());
        $this->assertFalse($globalFunc2Parameters[2]->isVariadic());

        $globalFunc3 = $phpFileModel->getFunction('globalFunc3');
        $this->assertSame($phpFileModel, $globalFunc3->getParentNode());
        $this->assertNull($globalFunc3->getDocumentation());
        $this->assertNull($globalFunc3->getVisibility());
        $this->assertTrue($globalFunc3->isGlobal());
        $this->assertFalse($globalFunc3->isAbstract());
        $this->assertFalse($globalFunc3->isFinal());
        $this->assertFalse($globalFunc3->isStatic());
        $this->assertSame($globalFunc3, $globalFunc3->getReturn()->getParentNode());
        $this->assertSame(TypeInterface::INT, $globalFunc3->getReturn()->getType());
        $this->assertFalse($globalFunc3->getReturn()->nullable());
        $globalFunc3Parameters = $globalFunc3->getParameters();
        $this->assertSame(3, $globalFunc3Parameters->count());
        $this->assertSame($globalFunc3, $globalFunc3Parameters[0]->getParentNode());
        $this->assertSame('argQualified', $globalFunc3Parameters[0]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc3Parameters[0]->getType());
        $this->assertSame('G', $globalFunc3Parameters[0]->getCustomType());
        $this->assertFalse($globalFunc3Parameters[0]->nullable());
        $this->assertFalse($globalFunc3Parameters[0]->isVariadic());
        $this->assertSame($globalFunc3, $globalFunc3Parameters[1]->getParentNode());
        $this->assertSame('argUnqualified', $globalFunc3Parameters[1]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc3Parameters[1]->getType());
        $this->assertSame('MyOtherClass', $globalFunc3Parameters[1]->getCustomType());
        $this->assertFalse($globalFunc3Parameters[1]->nullable());
        $this->assertFalse($globalFunc3Parameters[1]->isVariadic());
        $this->assertSame($globalFunc3, $globalFunc3Parameters[2]->getParentNode());
        $this->assertSame('argSubClassA', $globalFunc3Parameters[2]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc3Parameters[2]->getType());
        $this->assertSame('MySub', $globalFunc3Parameters[2]->getCustomType());
        $this->assertFalse($globalFunc3Parameters[2]->nullable());
        $this->assertFalse($globalFunc3Parameters[2]->isVariadic());

        $globalFunc4 = $phpFileModel->getFunction('globalFunc4');
        $this->assertSame($phpFileModel, $globalFunc4->getParentNode());
        $this->assertNull($globalFunc4->getDocumentation());
        $this->assertNull($globalFunc4->getVisibility());
        $this->assertTrue($globalFunc4->isGlobal());
        $this->assertFalse($globalFunc4->isAbstract());
        $this->assertFalse($globalFunc4->isFinal());
        $this->assertFalse($globalFunc4->isStatic());
        $this->assertSame($globalFunc4, $globalFunc4->getReturn()->getParentNode());
        $this->assertSame(TypeInterface::MIXED, $globalFunc4->getReturn()->getType());
        $this->assertFalse($globalFunc4->getReturn()->nullable());
        $globalFunc4Parameters = $globalFunc4->getParameters();
        $this->assertSame(3, $globalFunc4Parameters->count());
        $this->assertSame($globalFunc4, $globalFunc4Parameters[0]->getParentNode());
        $this->assertSame('argH', $globalFunc4Parameters[0]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc4Parameters[0]->getType());
        $this->assertSame('SomeClass', $globalFunc4Parameters[0]->getCustomType());
        $this->assertFalse($globalFunc4Parameters[0]->nullable());
        $this->assertFalse($globalFunc4Parameters[0]->isVariadic());
        $this->assertSame($globalFunc4, $globalFunc4Parameters[1]->getParentNode());
        $this->assertSame('argF', $globalFunc4Parameters[1]->getName());
        $this->assertSame(TypeInterface::CUSTOM, $globalFunc4Parameters[1]->getType());
        $this->assertSame('F', $globalFunc4Parameters[1]->getCustomType());
        $this->assertFalse($globalFunc4Parameters[1]->nullable());
        $this->assertFalse($globalFunc4Parameters[1]->isVariadic());
        $this->assertSame($globalFunc4, $globalFunc4Parameters[2]->getParentNode());
        $this->assertSame('variadic', $globalFunc4Parameters[2]->getName());
        $this->assertSame(TypeInterface::MIXED, $globalFunc4Parameters[2]->getType());
        $this->assertFalse($globalFunc4Parameters[2]->nullable());
        $this->assertTrue($globalFunc4Parameters[2]->isVariadic());

        // Check final class
        $finalClass = $phpFileModel->getClasses()[0];
        $this->assertSame($phpFileModel, $finalClass->getParentNode());
        $this->assertSame('AFinalClassWithGetter', $finalClass->getName());
        $this->assertSame("/**\n * Another doc for Class.\n */", $finalClass->getDocumentation());
        $this->assertTrue($finalClass->isFinal());
        $this->assertFalse($finalClass->isAbstract());

        // Properties
        $this->assertSame(2, $finalClass->getAttributes()->count());
        $fooAttribute = $finalClass->getAttribute('foo');
        $this->assertSame($finalClass, $fooAttribute->getParentNode());
        $this->assertTrue($fooAttribute->isStatic());
        $this->assertSame(VisibilityInterface::PRIVATE, $fooAttribute->getVisibility());
        $barAttribute = $finalClass->getAttribute('bar');
        $this->assertSame($finalClass, $barAttribute->getParentNode());
        $this->assertFalse($barAttribute->isStatic());
        $this->assertSame(VisibilityInterface::PRIVATE, $barAttribute->getVisibility());

        // Methods and auto annotations
        $finalClassMethods = $finalClass->getFunctions();
        $this->assertSame(6, $finalClassMethods->count());
        $this->assertSame($finalClass, $finalClassMethods[0]->getParentNode());
        $this->assertSame('getFoo', $finalClassMethods[0]->getName());
        $this->assertSame(VisibilityInterface::PUBLIC, $finalClassMethods[0]->getVisibility());
        $this->assertTrue($finalClassMethods[0]->isStatic());
        $this->assertFalse($finalClassMethods[0]->isAbstract());
        $this->assertFalse($finalClassMethods[0]->isFinal());
        $this->assertNotNull($finalClassMethods[0]->getGetAnnotation());
        $this->assertSame('foo', $finalClassMethods[0]->getGetAnnotation()->getProperty());
        $this->assertSame($finalClass, $finalClassMethods[1]->getParentNode());
        $this->assertSame('setFoo', $finalClassMethods[1]->getName());
        $this->assertSame(VisibilityInterface::PUBLIC, $finalClassMethods[1]->getVisibility());
        $this->assertTrue($finalClassMethods[1]->isStatic());
        $this->assertFalse($finalClassMethods[1]->isAbstract());
        $this->assertFalse($finalClassMethods[1]->isFinal());
        $this->assertNotNull($finalClassMethods[1]->getSetAnnotation());
        $this->assertSame('foo', $finalClassMethods[1]->getSetAnnotation()->getProperty());
        $this->assertSame($finalClass, $finalClassMethods[2]->getParentNode());
        $this->assertSame('getBar', $finalClassMethods[2]->getName());
        $this->assertSame(VisibilityInterface::PUBLIC, $finalClassMethods[2]->getVisibility());
        $this->assertFalse($finalClassMethods[2]->isStatic());
        $this->assertFalse($finalClassMethods[2]->isAbstract());
        $this->assertFalse($finalClassMethods[2]->isFinal());
        $this->assertNotNull($finalClassMethods[2]->getGetAnnotation());
        $this->assertSame('bar', $finalClassMethods[2]->getGetAnnotation()->getProperty());
        $this->assertSame($finalClass, $finalClassMethods[3]->getParentNode());
        $this->assertSame('setBar', $finalClassMethods[3]->getName());
        $this->assertSame(VisibilityInterface::PUBLIC, $finalClassMethods[3]->getVisibility());
        $this->assertFalse($finalClassMethods[3]->isStatic());
        $this->assertFalse($finalClassMethods[3]->isAbstract());
        $this->assertFalse($finalClassMethods[3]->isFinal());
        $this->assertNotNull($finalClassMethods[3]->getSetAnnotation());
        $this->assertSame('bar', $finalClassMethods[3]->getSetAnnotation()->getProperty());
        $this->assertSame($finalClass, $finalClassMethods[4]->getParentNode());
        $this->assertSame('getBaz', $finalClassMethods[4]->getName());
        $this->assertSame(VisibilityInterface::PUBLIC, $finalClassMethods[4]->getVisibility());
        $this->assertNull($finalClassMethods[4]->getGetAnnotation());
        $this->assertSame($finalClass, $finalClassMethods[5]->getParentNode());
        $this->assertSame('setBaz', $finalClassMethods[5]->getName());
        $this->assertSame(VisibilityInterface::PUBLIC, $finalClassMethods[5]->getVisibility());
        $this->assertNull($finalClassMethods[5]->getSetAnnotation());

        // Check simple class
        $class = $phpFileModel->getClasses()[1];
        $this->assertSame($phpFileModel, $class->getParentNode());
        $this->assertSame('AClass', $class->getName());
        $this->assertNull($class->getDocumentation());
        $this->assertFalse($class->isFinal());
        $this->assertFalse($class->isAbstract());

        $this->assertSame(2, $class->getAttributes()->count());
        $this->assertSame(VisibilityInterface::PUBLIC, $class->getAttribute('withDefaultProperty')->getVisibility());
        $this->assertNull($class->getAttribute('withDefaultProperty')->getValue());
        $this->assertFalse($class->getAttribute('withDefaultProperty')->isStatic());
        $this->assertSame(VisibilityInterface::PUBLIC, $class->getAttribute('staticProperty')->getVisibility());
        $this->assertTrue($class->getAttribute('staticProperty')->isStatic());

        $this->assertSame(VisibilityInterface::PUBLIC, $class->getFunction('staticFunc')->getVisibility());
        $this->assertTrue($class->getFunction('staticFunc')->isStatic());
        $this->assertFalse($class->getFunction('staticFunc')->isFinal());
        $this->assertFalse($class->getFunction('staticFunc')->isAbstract());
        $this->assertFalse($class->getFunction('staticFunc')->isGlobal());

        $this->assertSame(VisibilityInterface::PUBLIC, $class->getFunction('finalFunc')->getVisibility());
        $this->assertFalse($class->getFunction('finalFunc')->isStatic());
        $this->assertTrue($class->getFunction('finalFunc')->isFinal());
        $this->assertFalse($class->getFunction('finalFunc')->isAbstract());
        $this->assertFalse($class->getFunction('finalFunc')->isGlobal());

        // Check abstract class and methods
        $this->assertSame('AAbstractClass', $phpFileModel->getClasses()[2]->getName());
        $this->assertSame($phpFileModel, $phpFileModel->getClasses()[2]->getParentNode());
        $this->assertNull($phpFileModel->getClasses()[2]->getDocumentation());
        $this->assertTrue($phpFileModel->getClasses()[2]->isAbstract());
        $this->assertFalse($phpFileModel->getClasses()[2]->isFinal());
        $this->assertTrue($phpFileModel->getClasses()[2]->getFunction('abstractFunction')->isAbstract());

        // Check trait properties and methods visibility
        $trait = $phpFileModel->getTraits()[0];
        $this->assertSame('ATrait', $trait->getName());
        $this->assertSame("/**\n * Another doc for Trait.\n */", $trait->getDocumentation());
        $this->assertSame($phpFileModel, $trait->getParentNode());
        $this->assertSame(3, $trait->getAttributes()->count());
        $this->assertSame(4, $trait->getFunctions()->count());
        $this->assertSame(VisibilityInterface::PUBLIC, $trait->getAttribute('publicProperty')->getVisibility());
        $this->assertSame(VisibilityInterface::PROTECTED, $trait->getAttribute('protectedProperty')->getVisibility());
        $this->assertSame(VisibilityInterface::PRIVATE, $trait->getAttribute('privateProperty')->getVisibility());
        $this->assertSame(VisibilityInterface::PUBLIC, $trait->getFunction('funcWithoutVisibility')->getVisibility());
        $this->assertSame(VisibilityInterface::PUBLIC, $trait->getFunction('publicFunc')->getVisibility());
        $this->assertSame(VisibilityInterface::PROTECTED, $trait->getFunction('protectedFunc')->getVisibility());
        $this->assertSame(VisibilityInterface::PRIVATE, $trait->getFunction('privateFunc')->getVisibility());

        // Check interface parsing
        $interface = $phpFileModel->getInterfaces()[0];
        $this->assertSame('AInterface', $interface->getName());
        $this->assertSame("/**\n * Another doc for Interface.\n */", $interface->getDocumentation());
        $this->assertSame($phpFileModel, $interface->getParentNode());
        $this->assertSame(1, $interface->getFunctions()->count());
        $this->assertNotNull($interface->getFunction('interfaceFunc'));

    }

    public function testNodeParsersWithoutNamespace(): void
    {
        $code = file_get_contents(__DIR__ . '/resource/without_namespace.php');

        /** @var PhpFileModelInterface $phpFileModel */
        $phpFileModel = self::$container->get(PhpParserInterface::class)->invoke($code);

        $this->assertSame([], $phpFileModel->getNamespace());
    }

    public function testNodeParsersWithoutAuto(): void
    {
        $code = file_get_contents(__DIR__ . '/resource/without_auto.php');

        /** @var PhpFileModelInterface $phpFileModel */
        $phpFileModel = self::$container->get(PhpParserInterface::class)->invoke($code);

        $class = $phpFileModel->getClasses()[0];
        $this->assertNull($class->getFunction('getProperty')->getGetAnnotation());
        $this->assertNull($class->getFunction('setProperty')->getSetAnnotation());
    }

    public function testNodeParsersWithoutInterface(): void
    {
        $code = file_get_contents(__DIR__ . '/resource/without_interface.php');

        /** @var PhpFileModelInterface $phpFileModel */
        $phpFileModel = self::$container->get(PhpParserInterface::class)->invoke($code);

        $this->assertSame(0, $phpFileModel->getInterfaces()->count());
    }

    public function testAttributeNodeParserThrowException(): void
    {
        $attributeNodeParser = self::$container->get(AttributeNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $attributeNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testClassNodeParserThrowException(): void
    {
        $classNodeParser = self::$container->get(ClassNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $classNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testDocumentationNodeParserThrowException(): void
    {
        $documentationNodeParser = self::$container->get(DocumentationNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $documentationNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testFunctionNodeParserThrowException(): void
    {
        $functionNodeParser = self::$container->get(FunctionNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $functionNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testGroupUseNodeParserThrowException(): void
    {
        $groupUseNodeParser = self::$container->get(GroupUseNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $groupUseNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testInterfaceNodeParserThrowException(): void
    {
        $container = (new ContainerFactory())->invoke(new BaseConfig([
            'auto' => false,
            'interface' => true,
            'phpdoc' => []
        ]));

        $interfaceNodeParser = $container->get(InterfaceNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $interfaceNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testMethodNodeParserThrowException(): void
    {
        $methodNodeParser = self::$container->get(MethodNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $methodNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testNamespaceNodeParserThrowException(): void
    {
        $namespaceNodeParser = self::$container->get(NamespaceNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $namespaceNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testParameterNodeParserThrowException(): void
    {
        $parameterNodeParser = self::$container->get(ParameterNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $parameterNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testPhpFileNodeParserThrowException(): void
    {
        $phpFileNodeParser = self::$container->get(PhpFileNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $phpFileNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testTraitNodeParserThrowException(): void
    {
        $traitNodeParser = self::$container->get(TraitNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $traitNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testTypeNodeParserThrowException(): void
    {
        $typeNodeParser = self::$container->get(TypeNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $typeNodeParser->invoke($this->createMock(NullableType::class), new PhpFileModel());
    }

    public function testUseNodeParserThrowException(): void
    {
        $useNodeParser = self::$container->get(UseNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $useNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testValueNodeParserThrowException(): void
    {
        $valueNodeParser = self::$container->get(ValueNodeParser::class);

        $this->expectException(Exception::class);

        // Call the parser with an invalid class will throw an exception
        $valueNodeParser->invoke(new \DateTime(), new PhpFileModel());
    }

    public function testAbstractNodeParserDoesNotHaveNodeParser(): void
    {
        $nodeParser = $this->getMockForAbstractClass(AbstractNodeParser::class);

        $getNodeParserMethod = (new \ReflectionClass($nodeParser))
            ->getMethod('getNodeParser');
        $getNodeParserMethod->setAccessible(true);

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The node parser for "UnExistingNodeParser" cannot be found');

        $getNodeParserMethod->invoke($nodeParser, 'UnExistingNodeParser');
    }

    public function testTypeNodeParserWithInvalidNodeToParse(): void
    {
        $typeNodeParser = new TypeNodeParser();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('TypeNodeParser is made to parse a type node');

        $typeNodeParser->invoke(new \DateTime(), $this->createMock(TypeInterface::class));
    }

    public function testTypeNodeParserWithUnknownType(): void
    {
        $typeNodeParser = new TypeNodeParser();

        $getClassTypeMethod = (new \ReflectionClass($typeNodeParser))
            ->getMethod('getClassType');
        $getClassTypeMethod->setAccessible(true);

        $nameMock = $this->createMock(Name::class);
        $nameMock->expects($this->once())->method('isFullyQualified')
            ->with()->willReturn(false);
        $nameMock->expects($this->once())->method('isRelative')
            ->with()->willReturn(false);
        $nameMock->expects($this->once())->method('isUnqualified')
            ->with()->willReturn(false);
        $nameMock->expects($this->once())->method('isQualified')
            ->with()->willReturn(false);

        $phpFile = new PhpFileModel();
        $function = new FunctionModel();
        $function->setParentNode($phpFile);
        $return = new ReturnModel();
        $return->setParentNode($function);

        $classType = $getClassTypeMethod->invoke(
            $typeNodeParser,
            $nameMock,
            $return
        );
        $this->assertSame('UNKNOWN_CUSTOM_TYPE', $classType);
    }
}
