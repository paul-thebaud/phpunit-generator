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
     * @param string $name The annotation name (such as "@PhpUnitGen\AssertEquals").
     * @param int    $line The line number in documentation block.
     *
     * @return AnnotationInterface The new built annotation.
     *
     * @throws AnnotationParseException If the annotation is unknown.
     */
    public function invoke(string $name, int $line): AnnotationInterface
    {
        $name = preg_replace('/@(?i)(PhpUnitGen|Pug)\\\\/', '', $name);
        switch (true) {
            case strcasecmp($name, 'get') === 0:
                $annotation = new GetAnnotation();
                break;
            case strcasecmp($name, 'set') === 0:
                $annotation = new SetAnnotation();
                break;
            case strcasecmp($name, 'construct') === 0:
                $annotation = new ConstructAnnotation();
                break;
            case strcasecmp($name, 'mock') === 0:
                $annotation = new MockAnnotation();
                break;
            case strcasecmp($name, 'params') === 0:
                $annotation = new ParamsAnnotation();
                break;
            case strcasecmp(substr($name, 0, 6), 'assert') === 0:
                $annotation = new AssertAnnotation();
                break;
            default:
                throw new AnnotationParseException(
                    sprintf('Annotation of name "%s" is unknown', $name)
                );
        }
        $annotation->setName($name);
        $annotation->setLine($line);
        return $annotation;
    }
}
