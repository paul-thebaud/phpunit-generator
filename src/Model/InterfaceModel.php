<?php

namespace PhpUnitGen\Model;

use Doctrine\Common\Collections\ArrayCollection;
use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Annotation\ConstructorAnnotation;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Model\PropertyTrait\ClassLikeTrait;
use PhpUnitGen\Model\PropertyTrait\DocumentationTrait;
use PhpUnitGen\Model\PropertyTrait\NameTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;

/**
 * Class InterfaceModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class InterfaceModel implements InterfaceModelInterface
{
    use NameTrait;
    use NodeTrait;
    use ClassLikeTrait;
    use DocumentationTrait;

    /**
     * InterfaceModel constructor.
     */
    public function __construct()
    {
        $this->functions   = new ArrayCollection();
        $this->annotations = new ArrayCollection();
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
}
