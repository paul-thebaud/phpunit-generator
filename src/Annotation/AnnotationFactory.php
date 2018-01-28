<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;

/**
 * Class AnnotationFactory.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AnnotationFactory
{
    /**
     * Build an annotation from a name and a content.
     *
     * @param string      $name    The annotation name (such as "@PhpUnitGen\AssertEquals").
     * @param int         $line    The line number in documentation block.
     *
     * @return AnnotationInterface The new built annotation.
     */
    public function invoke(string $name, int $line): AnnotationInterface
    {
        /** @todo */
        $annotation = new GetterAnnotation();
        $annotation->setName($name);
        $annotation->setLine($line);
        return $annotation;
    }
}
