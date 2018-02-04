<?php

namespace PhpUnitGen\Model\PropertyTrait;

use Doctrine\Common\Collections\Collection;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\ConstructorAnnotation;
use PhpUnitGen\Annotation\GetterAnnotation;
use PhpUnitGen\Annotation\SetterAnnotation;

/**
 * Trait DocumentationTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait DocumentationTrait
{
    /**
     * @var string|null $documentation The documentation.
     */
    protected $documentation;

    /**
     * @var Collection|AnnotationInterface[] $annotations The annotations contained in the documentation.
     */
    protected $annotations;

    /**
     * @param string|null $documentation The new documentation to be set.
     */
    public function setDocumentation(?string $documentation): void
    {
        $this->documentation = $documentation;
    }

    /**
     * @return string|null The current documentation.
     */
    public function getDocumentation(): ?string
    {
        return $this->documentation;
    }

    /**
     * @param AnnotationInterface $annotation The annotation to add.
     */
    public function addAnnotation(AnnotationInterface $annotation): void
    {
        $this->annotations->add($annotation);
    }

    /**
     * @return ConstructorAnnotation|null The constructor annotation, null if none.
     */
    public function getConstructorAnnotation(): ?ConstructorAnnotation
    {
        $annotations = $this->annotations->filter(function (AnnotationInterface $annotation) {
            return $annotation->getType() === AnnotationInterface::TYPE_CONSTRUCTOR;
        });
        if ($annotations->isEmpty()) {
            return null;
        }
        return $annotations->first();
    }

    /**
     * @return GetterAnnotation|null The getter annotation, null if none.
     */
    public function getGetterAnnotation(): ?GetterAnnotation
    {
        $annotations = $this->annotations->filter(function (AnnotationInterface $annotation) {
            return $annotation->getType() === AnnotationInterface::TYPE_GETTER;
        });
        if ($annotations->isEmpty()) {
            return null;
        }
        return $annotations->first();
    }

    /**
     * @return SetterAnnotation|null The setter annotation, null if none.
     */
    public function getSetterAnnotation(): ?SetterAnnotation
    {
        $annotations = $this->annotations->filter(function (AnnotationInterface $annotation) {
            return $annotation->getType() === AnnotationInterface::TYPE_SETTER;
        });
        if ($annotations->isEmpty()) {
            return null;
        }
        return $annotations->first();
    }

    /**
     * @return Collection|AnnotationInterface[] The mock annotations.
     */
    public function getMockAnnotations(): Collection
    {
        return $this->annotations->filter(function (AnnotationInterface $annotation) {
            return $annotation->getType() === AnnotationInterface::TYPE_MOCK;
        });
    }

    /**
     * @return Collection|AnnotationInterface[] The assertion annotations.
     */
    public function getAssertAnnotations(): Collection
    {
        return $this->annotations->filter(function (AnnotationInterface $annotation) {
            return $annotation->getType() === AnnotationInterface::TYPE_ASSERT;
        });
    }
}
