<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Annotation\SetAnnotation;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserUtil\MethodVisibilityHelper;
use Respect\Validation\Validator;

/**
 * Class MethodNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class MethodNodeParser extends AbstractFunctionNodeParser
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Stmt\ClassMethod || ! $parent instanceof InterfaceModelInterface) {
            throw new Exception('MethodNodeParser is made to parse a method node');
        }

        $function = new FunctionModel();
        $function->setParentNode($parent);
        $function->setName($node->name);
        $function->setIsFinal($node->isFinal());
        $function->setIsStatic($node->isStatic());
        $function->setIsAbstract($node->isAbstract());
        $function->setVisibility(MethodVisibilityHelper::getMethodVisibility($node));

        $this->parseFunction($node, $function);

        $parent->addFunction($function);

        if ($this->autoGetterOrSetter($function, $parent)) {
            return;
        }
        if (($documentation = $node->getDocComment()) !== null) {
            $this->documentationNodeParser->invoke($documentation, $function);
        }
    }

    /**
     * Check if auto generation is enabled, and try to generate getter or setter annotation.
     *
     * @param FunctionModel           $function The function to check for.
     * @param InterfaceModelInterface $parent   The parent to use.
     *
     * @return bool True if a getter or a setter annotation has been created.
     */
    private function autoGetterOrSetter(FunctionModel $function, InterfaceModelInterface $parent): bool
    {
        if ($this->config->hasAuto() && $parent instanceof TraitModelInterface) {
            if ($this->getter($function, $parent)) {
                return true;
            }
            if ($this->setter($function, $parent)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Try to create a getter annotation for function.
     *
     * @param FunctionModel       $function The function to check for.
     * @param TraitModelInterface $parent The parent to use.
     *
     * @return bool True if a getter annotation has been created.
     */
    private function getter(FunctionModel $function, TraitModelInterface $parent): bool
    {
        // Check if function name matches
        preg_match('/^get(.+)$/', $function->getName(), $matches);

        if (Validator::arrayType()->length(2, 2)->validate($matches)) {
            // Check if property exists
            $property = lcfirst($matches[1]);
            if ($parent->hasAttribute($property, $function->isStatic())) {
                $annotation = new GetAnnotation();
                $annotation->setName('@PhpUnitGen\\getter');
                $function->addAnnotation($annotation);
                $annotation->setParentNode($function);
                $annotation->compile();

                return true;
            }
        }

        return false;
    }

    /**
     * Try to create a setter annotation for function.
     *
     * @param FunctionModel       $function The function to check for.
     * @param TraitModelInterface $parent The parent to use.
     *
     * @return bool True if a setter annotation has been created.
     */
    private function setter(FunctionModel $function, TraitModelInterface $parent): bool
    {
        // Check if function name matches
        preg_match('/^set(.+)$/', $function->getName(), $matches);

        if (Validator::arrayType()->length(2, 2)->validate($matches)) {
            // Check if property exists
            $property = lcfirst($matches[1]);
            if ($parent->hasAttribute($property, $function->isStatic())) {
                $annotation = new SetAnnotation();
                $annotation->setName('@PhpUnitGen\\setter');
                $annotation->setParentNode($function);
                $function->addAnnotation($annotation);
                $annotation->compile();

                return true;
            }
        }

        return false;
    }
}
