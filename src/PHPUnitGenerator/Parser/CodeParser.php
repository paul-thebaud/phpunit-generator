<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Exception\EmptyFileException;
use PHPUnitGenerator\Exception\InvalidCodeException;
use PHPUnitGenerator\Exception\NoMethodFoundException;
use PHPUnitGenerator\Model\ArgumentModel;
use PHPUnitGenerator\Model\ClassModel;
use PHPUnitGenerator\Model\MethodModel;
use PHPUnitGenerator\Model\ModelInterface\ArgumentModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Model\ModelInterface\ModifierInterface;
use PHPUnitGenerator\Model\ModelInterface\TypeInterface;
use PHPUnitGenerator\Parser\ParserInterface\CodeParserInterface;

/**
 * Class CodeParser
 *
 *      An implementation of CodeParserInterface using Nikic PHP Parser
 *
 * @see     https://github.com/nikic/PHP-Parser
 *
 * @package PHPUnitGenerator\Parser
 */
class CodeParser implements CodeParserInterface
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * @var Parser $phpParser The PHP code parser
     */
    private $phpParser;

    /**
     * @var array $mappingUse An array which map class names and complete class
     *      names
     */
    private $mappingClassNames = [];

    /**
     * CodeParser constructor.
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;

        $this->phpParser = (new ParserFactory())
            ->create(ParserFactory::PREFER_PHP7);
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $code): ClassModelInterface
    {
        // Parse the code
        try {
            $statements = $this->phpParser->parse($code);
        } catch (Error $error) {
            throw new InvalidCodeException(InvalidCodeException::TEXT);
        }

        // Search if there is a namespace
        $namespaceName = null;
        $namespaceStatement = $this->findNamespace($statements);
        // If namespace is defined, search in namespace statements
        if ($namespaceStatement !== null) {
            $statements = $namespaceStatement->stmts ?? [];
            $namespaceName = $namespaceStatement->name ? $namespaceStatement->name->toString() : null;
        }

        // Parse class
        $classStatement = $this->findClassAndUses($statements);
        if (! $classStatement) {
            throw new EmptyFileException(EmptyFileException::TEXT);
        }

        // Create class model
        $classModel = new ClassModel($classStatement->name);
        if ($namespaceName !== null) {
            $classModel->setNamespaceName($namespaceName);
        }
        $classModel = $this->parseTypeAndModifier($classModel, $classStatement);

        // Add "self" as an alias of class name to mappingClassNames
        $this->mappingClassNames['self'] = $classModel->getCompleteName();

        // Add future tests documentation
        $classModel->setTestsAnnotations($this->getTestsAnnotations());

        // Parse class methods
        $classModel->setMethods($this->parseMethods($classModel, $classStatement->stmts));

        if (count($classModel->getMethods()) === 0) {
            throw new NoMethodFoundException(
                sprintf(NoMethodFoundException::TEXT, $classModel->getName())
            );
        }

        // Parse class properties
        $classModel->setProperties(
            $this->parseProperties($classStatement->stmts)
        );

        return $classModel;
    }

    /**
     * Get the namespace statement if exists, else return null
     *
     * @param Node[] $statements
     *
     * @return Namespace_|null
     */
    protected function findNamespace(array $statements)
    {
        foreach ($statements as $statement) {
            if ($statement instanceof Namespace_) {
                return $statement;
            }
        }
        return null;
    }

    /**
     * Find class statement if exists, and parse uses statement
     *
     * @param array $statements
     *
     * @return Class_|Trait_|Interface_|null
     */
    protected function findClassAndUses(array $statements)
    {
        $classStatement = null;
        foreach ($statements as $statement) {
            if ($statement instanceof Use_ && $statement->type === Use_::TYPE_NORMAL) {
                $this->parseUseStatement($statement);
            } else {
                // No break, because uses statement can be after a class statement
                $classStatement = $this->findClass($statement);
            }
        }
        return $classStatement;
    }

    /**
     * Find class in statement if exists
     *
     * @param Node $statement
     *
     * @return Class_|Trait_|Interface_|null
     */
    protected function findClass(Node $statement)
    {
        if ($statement instanceof Class_ || $statement instanceof Trait_ || $statement instanceof Interface_) {
            return $statement;
        }
        return null;
    }

    /**
     * Parse a "use" statement and save mapping between alias and class name
     *
     * @param Use_ $statement
     */
    protected function parseUseStatement(Use_ $statement)
    {
        foreach ($statement->uses as $use) {
            if ($use->alias) {
                $this->mappingClassNames[$use->alias] = $use->name->toString();
            } else {
                $this->mappingClassNames[$use->name->getLast()] = $use->name->toString();
            }
        }
    }

    protected function parseTypeAndModifier(ClassModelInterface $classModel, Node $statement): ClassModelInterface
    {
        if ($statement instanceof Class_) {
            if ($statement->isFinal()) {
                $classModel->setModifier(ModifierInterface::MODIFIER_FINAL);
            } elseif ($statement->isAbstract()) {
                $classModel->setModifier(ModifierInterface::MODIFIER_ABSTRACT);
            }
        } elseif ($statement instanceof Interface_) {
            $classModel->setType(ClassModelInterface::TYPE_INTERFACE);
        } elseif ($statement instanceof Trait_) {
            $classModel->setType(ClassModelInterface::TYPE_TRAIT);
        }
        return $classModel;
    }

    /**
     * Parse class statements to find properties
     *
     * @param Node[] $statements
     *
     * @return string[]
     */
    protected function parseProperties(array $statements): array
    {
        $properties = [];
        foreach ($statements as $statement) {
            if ($statement instanceof Property) {
                $properties[] = $this->parseProperty($statement);
            }
        }
        return $properties;
    }

    /**
     * Parse a "property" statement to get all declared properties
     *
     * @param Property $statement
     *
     * @return array
     */
    protected function parseProperty(Property $statement): array
    {
        $properties = [];
        foreach ($statement->props as $property) {
            $properties[] = $property->name;
        }
        return $properties;
    }

    /**
     * Get the tests annotations from the configuration
     *
     * @return array
     */
    protected function getTestsAnnotations(): array
    {
        $testsAnnotations = [];
        if (! empty($author = $this->config->getOption(ConfigInterface::OPTION_DOC_AUTHOR))) {
            $testsAnnotations['author'] = $author;
        }
        if (! empty($copyright = $this->config->getOption(ConfigInterface::OPTION_DOC_COPYRIGHT))) {
            $testsAnnotations['copyright'] = $copyright;
        }
        if (! empty($licence = $this->config->getOption(ConfigInterface::OPTION_DOC_LICENCE))) {
            $testsAnnotations['licence'] = $licence;
        }
        if (! empty($since = $this->config->getOption(ConfigInterface::OPTION_DOC_SINCE))) {
            $testsAnnotations['since'] = $since;
        }
        return $testsAnnotations;
    }

    /**
     * Parse class statements to find methods
     *
     * @param ClassModelInterface $classModel
     * @param Node[]              $statements
     *
     * @return MethodModelInterface[]
     */
    protected function parseMethods(
        ClassModelInterface $classModel,
        array $statements
    ): array {
        $methods = [];
        foreach ($statements as $statement) {
            if ($statement instanceof ClassMethod) {
                $methodModel = new MethodModel($classModel, $statement->name);

                // Get method visibility
                if ($statement->isProtected()) {
                    $methodModel->setVisibility(MethodModelInterface::VISIBILITY_PROTECTED);
                } elseif ($statement->isPrivate()) {
                    $methodModel->setVisibility(MethodModelInterface::VISIBILITY_PRIVATE);
                }

                // Get method modifier
                $modifiers = [];
                if ($statement->isStatic()) {
                    $modifiers[] = ModifierInterface::MODIFIER_STATIC;
                }
                if ($statement->isFinal()) {
                    $modifiers[] = ModifierInterface::MODIFIER_FINAL;
                } elseif ($statement->isAbstract()) {
                    $modifiers[] = ModifierInterface::MODIFIER_ABSTRACT;
                }
                $methodModel->setModifiers($modifiers);

                // Get method arguments
                $methodModel->setArguments(
                    $this->parseArguments(
                        $methodModel,
                        $statement->getParams()
                    )
                );

                // Get method return type
                $returnType = $statement->getReturnType();
                if ($returnType instanceof NullableType) {
                    $returnType = $returnType->type;
                    $methodModel->setReturnNullable(true);
                }
                $methodModel->setReturnType($this->parseType($methodModel->getParentClass(), $returnType));

                // Get method documentation
                if ($statement->getDocComment()) {
                    $methodModel->setDocumentation($statement->getDocComment()->getText());
                }

                $methods[] = $methodModel;
            }
        }
        return $methods;
    }

    /**
     * Parse method arguments to create them
     *
     * @param MethodModelInterface $methodModel
     * @param Param[]              $statements
     *
     * @return ArgumentModelInterface[]
     */
    protected function parseArguments(
        MethodModelInterface $methodModel,
        array $statements
    ): array {
        $arguments = [];
        foreach ($statements as $statement) {
            if ($statement instanceof Param) {
                $argumentModel = new ArgumentModel(
                    $methodModel,
                    $statement->name
                );

                // Get argument type
                $type = $statement->type;
                if ($type instanceof NullableType) {
                    $type = $type->type;
                    $argumentModel->setNullable(true);
                }
                $argumentModel->setType($this->parseType($methodModel->getParentClass(), $type));

                // @todo: Add default value
                // $argumentModel->setDefaultValue();

                $arguments[] = $argumentModel;
            }
        }
        return $arguments;
    }

    /**
     * Parse a type to get a valid TypeInterface type
     *
     * @param ClassModelInterface $classModel
     * @param Name|string         $type
     *
     * @return string
     */
    protected function parseType(ClassModelInterface $classModel, $type): string
    {
        // Its empty
        if ($type === null) {
            return TypeInterface::TYPE_MIXED;
        }
        // Its an object
        if ($type instanceof Name) {
            $type = $type->__toString();
            if (substr($type, 0, 1) === '\\') {
                return substr($type, 1);
            }
            if (isset($this->mappingClassNames[$type])) {
                return $this->mappingClassNames[$type];
            }
            if (class_exists('\\' . $type)) {
                return $type;
            }
            return ($classModel->getNamespaceName() ? ($classModel->getNamespaceName() . '\\') : '') . $type;
        }
        return constant(TypeInterface::class . '::TYPE_' . strtoupper($type));
    }
}
