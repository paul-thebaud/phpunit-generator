<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Util\Json;

/**
 * Class SetterAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class SetterAnnotation extends AbstractAnnotation
{
    /**
     * @var string $property The name of the property to get.
     */
    private $property;

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_SETTER;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(): void
    {
        if (strlen($this->getStringContent()) > 0) {
            // Decode JSON content
            try {
                $decoded = Json::decode($this->getStringContent());
            } catch (JsonException $exception) {
                throw new AnnotationParseException('"getter" annotation content is invalid (invalid JSON content)');
            }
            if (! is_string($decoded)) {
                throw new AnnotationParseException(
                    '"getter" annotation content is invalid (property name must be a string)'
                );
            }
            $this->property = $decoded;
        } else {
            $this->property = preg_replace('/^get/', '', $this->getParentNode()->getName());
            $this->property = lcfirst($this->property);
        }
    }

    /**
     * @return string The name of the property to get.
     */
    public function getProperty(): string
    {
        return $this->property;
    }
}
