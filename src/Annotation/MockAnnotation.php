<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Parser\NodeParserUtil\RootRetrieverHelper;
use PhpUnitGen\Util\Json;
use Respect\Validation\Validator;

/**
 * Class MockAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class MockAnnotation extends AbstractAnnotation
{
    /**
     * @var string $property The name of the property which will contains the mock.
     */
    private $property;

    /**
     * @var string $class The class to the mock.
     */
    private $class;

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_MOCK;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(): void
    {
        // Decode JSON content
        try {
            $decoded = Json::decode('[' . $this->getStringContent() . ']');
        } catch (JsonException $exception) {
            throw new AnnotationParseException('"mock" annotation content is invalid (invalid JSON content)');
        }
        $this->validate($decoded);

        $phpFile = RootRetrieverHelper::getRoot($this);
        // If class to use is not from global namespace
        if (! Validator::regex('/^\\\\/')->validate($decoded[0])) {
            // Add the current namespace to it
            $namespace  = $phpFile->getNamespaceString();
            $decoded[0] = ($namespace !== null? ($namespace . '\\') : '') . $decoded[0];
        }
        // Get the last name part
        $nameArray = explode('\\', $decoded[0]);
        $lastPart  = end($nameArray);
        // Add use to PhpFile
        $phpFile->addConcreteUse($decoded[0], $lastPart);
        $this->class    = $lastPart;
        $this->property = $decoded[1];
    }

    /**
     * Validate the content of annotation.
     *
     * @param mixed $decoded The annotation content.
     *
     * @throws AnnotationParseException If the content is invalid.
     */
    private function validate($decoded): void
    {
        // Validate it is an array
        if (! Validator::arrayType()->length(2, 2)->validate($decoded)) {
            throw new AnnotationParseException(
                '"mock" annotation content is invalid (must contains the class to mock and the property name)'
            );
        }
        // Validate that each value is a string
        if (! Validator::arrayVal()->each(Validator::stringType(), Validator::intType())->validate($decoded)) {
            throw new AnnotationParseException(
                '"mock" annotation content is invalid (class and property name must be string)'
            );
        }
    }

    /**
     * @return string The name of the property which will contains the mock.
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string The class to the mock.
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
