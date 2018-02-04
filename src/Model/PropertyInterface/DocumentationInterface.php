<?php

namespace PhpUnitGen\Model\PropertyInterface;

use Doctrine\Common\Collections\Collection;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\ConstructorAnnotation;
use PhpUnitGen\Annotation\GetterAnnotation;
use PhpUnitGen\Annotation\SetterAnnotation;

/**
 * Interface DocumentationInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface DocumentationInterface extends NodeInterface
{
    /**
     * @param string|null $documentation The new documentation to be set.
     */
    public function setDocumentation(?string $documentation): void;

    /**
     * @return string|null The current documentation.
     */
    public function getDocumentation(): ?string;

    /**
     * @param AnnotationInterface $annotation The annotation to add.
     */
    public function addAnnotation(AnnotationInterface $annotation): void;

    /**
     * @return ConstructorAnnotation|null The constructor annotation, null if none.
     */
    public function getConstructorAnnotation(): ?ConstructorAnnotation;

    /**
     * @return GetterAnnotation|null The getter annotation, null if none.
     */
    public function getGetterAnnotation(): ?GetterAnnotation;

    /**
     * @return SetterAnnotation|null The setter annotation, null if none.
     */
    public function getSetterAnnotation(): ?SetterAnnotation;

    /**
     * @return Collection|AnnotationInterface[] The mock annotations.
     */
    public function getMockAnnotations(): Collection;

    /**
     * @return Collection|AnnotationInterface[] The assertion annotations.
     */
    public function getAssertAnnotations(): Collection;
}
