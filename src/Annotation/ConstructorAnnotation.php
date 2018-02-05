<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;

/**
 * Class ConstructorAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ConstructorAnnotation extends AbstractAnnotation
{
    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_CONSTRUCTOR;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(): void
    {
        /*
        if ($this->getStringContent() !== null
            && ! Validator::regex('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/')
                ->validate($this->getStringContent())
        ) {
            throw new AnnotationParseException(sprintf(
                'The annotation at line %d of documentation contains an invalid property name.',
                $this->getLine()
            ));
        }
        */
    }
}
