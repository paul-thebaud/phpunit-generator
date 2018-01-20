<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\TypeInterface;
use PhpUnitGen\Util\RootRetrieverTrait;
use Respect\Validation\Validator;

/**
 * Class TypeNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class TypeNodeParser extends AbstractNodeParser
{
    use RootRetrieverTrait;

    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param TypeInterface $parent The parent node.
     *
     * @return TypeInterface  The updated parent.
     */
    public function invoke($node, TypeInterface $parent): TypeInterface
    {
        // If it is a nullable type
        if ($node instanceof Node\NullableType) {
            $parent->setNullable(true);

            return $this->invoke($node->type, $parent);
        }

        // If it is a class like type
        if ($node instanceof Node\Name) {
            $parent->setType(TypeInterface::CUSTOM);
            $parent->setCustomType($this->getClassType($node, $parent));

            return $parent;
        }

        // If it is a scalar type
        if (Validator::stringType()->validate($node)) {
            $parent->setType(constant(TypeInterface::class . '::' . strtoupper($node)));
        }

        return $parent;
    }

    /**
     * Get the class type hint as a string.
     *
     * @param Node\Name     $node   The name node to parse.
     * @param TypeInterface $parent The parent to update.
     *
     * @return string The class type hint.
     */
    private function getClassType(Node\Name $node, TypeInterface $parent): string
    {
        $phpFile = $this->getRoot($parent);

        switch (true) {
            case $node->isFullyQualified():
                $name = $node->toString();
                break;
            case $node->isRelative():
            case $node->isUnqualified():
                $name = $this->getUnqualifiedClassType($node, $phpFile);
                break;
            case $node->isQualified():
                $name = $this->getQualifiedClassType($node, $phpFile);
                break;
            default:
                $name = TypeInterface::UNKNOWN_CUSTOM;
                break;
        }

        // Get the last name part
        $nameArray = explode('\\', $name);
        $lastPart  = end($nameArray);

        $phpFile->addConcreteUse($name, $lastPart);

        return $name;
    }

    /**
     * Retrieve the class type hint when it is qualified.
     *
     * @param Node\Name             $node    The name node to parse.
     * @param PhpFileModelInterface $phpFile The php file.
     *
     * @return string The class type hint.
     */
    private function getUnqualifiedClassType(Node\Name $node, PhpFileModelInterface $phpFile): string
    {
        $name = $node->toString();
        if ($phpFile->hasUse($name)) {
            return $phpFile->getUse($name);
        }
        $namespace = $phpFile->getNamespaceString();
        if ($namespace !== null) {
            $namespace = $namespace . '\\';
        }
        return $namespace . $name;
    }

    /**
     * Retrieve the class type hint when it is qualified.
     *
     * @param Node\Name             $node    The name node to parse.
     * @param PhpFileModelInterface $phpFile The php file.
     *
     * @return string The class type hint.
     */
    private function getQualifiedClassType(Node\Name $node, PhpFileModelInterface $phpFile): string
    {
        $path      = $node->toString();
        $firstPart = $node->getFirst();

        if ($phpFile->hasUse($firstPart)) {
            return str_replace($firstPart, $phpFile->getUse($firstPart), $path);
        }
        if ($firstPart === $phpFile->getNamespaceLast()) {
            return str_replace($firstPart, $phpFile->getNamespaceString(), $path);
        }

        return $path;
    }
}
