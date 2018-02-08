<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Annotation\GetAnnotation;
use PhpUnitGen\Annotation\SetAnnotation;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
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
     * @param Node\Stmt\ClassMethod   $node   The node to parse.
     * @param InterfaceModelInterface $parent The parent node.
     *
     * @return InterfaceModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\ClassMethod $node, InterfaceModelInterface $parent): InterfaceModelInterface
    {
        $function = new FunctionModel();
        $function->setParentNode($parent);
        $function->setName($node->name);
        $function->setIsFinal($node->isFinal());
        $function->setIsStatic($node->isStatic());
        $function->setIsAbstract($node->isAbstract());
        $function->setVisibility(MethodVisibilityHelper::getMethodVisibility($node));

        $function = $this->parseFunction($node, $function);

        $parent->addFunction($function);

        if ($this->config->hasAuto()) {
            if ($this->getter($function)) {
                return $parent;
            }
            if ($this->setter($function)) {
                return $parent;
            }
        }
        if (($documentation = $node->getDocComment()) !== null) {
            $this->documentationNodeParser->invoke($documentation, $function);
        }

        return $parent;
    }

    private function getter(FunctionModel $function): bool
    {
        // Check if function name matches
        preg_match('/^get(.+)$/', $function->getName(), $matches);

        if (Validator::arrayType()->length(2, 2)->validate($matches)) {
            // Check if property exists
            $property = lcfirst($matches[1]);
            if ($function->getParentNode()->hasAttribute($property, $function->isStatic())) {
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

    private function setter(FunctionModel $function): bool
    {
        // Check if function name matches
        preg_match('/^set(.+)$/', $function->getName(), $matches);

        if (Validator::arrayType()->length(2, 2)->validate($matches)) {
            // Check if property exists
            $property = lcfirst($matches[1]);
            if ($function->getParentNode()->hasAttribute($property, $function->isStatic())) {
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
