<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Model\PropertyInterface\DocumentationInterface;
use Respect\Validation\Validator;

/**
 * Class AnnotationRegister.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AnnotationRegister
{
    private const ALLOWED_ON_GLOBAL_OR_PRIVATE = [
        AnnotationInterface::TYPE_ASSERT,
        AnnotationInterface::TYPE_MOCK,
        AnnotationInterface::TYPE_PARAMS
    ];

    /**
     * Register all annotations in the parent depending on annotation type.
     *
     * @param DocumentationInterface $parent      The parent to register annotation in.
     * @param array                  $annotations Annotations to register.
     *
     * @throws AnnotationParseException If the annotation can not compile.
     */
    public function invoke(DocumentationInterface $parent, array $annotations): void
    {
        foreach ($annotations as $annotation) {
            if ($parent instanceof FunctionModelInterface) {
                // Parent is a function, save params, assertions and mocks annotations.
                $this->saveFunctionAnnotation($parent, $annotation);
            }
            if ($parent instanceof InterfaceModelInterface) {
                // Parent is a class like, save mock and constructor annotations.
                $this->saveClassLikeAnnotation($parent, $annotation);
            }
        }
    }

    /**
     * Register an annotation for a function.
     *
     * @param FunctionModelInterface $parent     The function to register annotation in.
     * @param AnnotationInterface    $annotation The annotation to register.
     *
     * @throws AnnotationParseException If the annotation can not compile.
     */
    private function saveFunctionAnnotation(FunctionModelInterface $parent, AnnotationInterface $annotation): void
    {
        if (! $parent->isGlobal() && $parent->isPublic()) {
            // For public or not global function, all annotation except constructor
            if ($annotation->getType() !== AnnotationInterface::TYPE_CONSTRUCT) {
                $annotation->setParentNode($parent);
                $parent->addAnnotation($annotation);
                $annotation->compile();
            }
        } else {
            // Restricted annotation on global or private functions.
            if (Validator::contains($annotation->getType())
                ->validate(AnnotationRegister::ALLOWED_ON_GLOBAL_OR_PRIVATE)) {
                $annotation->setParentNode($parent);
                $parent->addAnnotation($annotation);
                $annotation->compile();
            }
        }
    }

    /**
     * Register an annotation for an interface, a trait or a class.
     *
     * @param InterfaceModelInterface $parent     The interface, trait or class, to register annotation in.
     * @param AnnotationInterface     $annotation The annotation to register.
     *
     * @throws AnnotationParseException If the annotation can not compile.
     */
    private function saveClassLikeAnnotation(InterfaceModelInterface $parent, AnnotationInterface $annotation): void
    {
        // If it is a mock, register in PhpFile
        if ($annotation->getType() === AnnotationInterface::TYPE_MOCK) {
            $parent = $parent->getParentNode();
            $annotation->setParentNode($parent);
            $parent->addAnnotation($annotation);
            $annotation->compile();
        } else {
            // If it is a constructor, register in the current $parent
            if ($annotation->getType() === AnnotationInterface::TYPE_CONSTRUCT) {
                $annotation->setParentNode($parent);
                $parent->addAnnotation($annotation);
                $annotation->compile();
            }
        }
    }
}
