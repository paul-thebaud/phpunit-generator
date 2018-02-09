<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Parser\NodeParserUtil\RootRetrieverHelper;
use PhpUnitGen\Util\Json;
use Respect\Validation\Validator;

/**
 * Class ConstructAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ConstructAnnotation extends AbstractAnnotation
{
    /**
     * @var string|null $class The class name to use to construct the instance, null if none.
     */
    private $class;

    /**
     * @var string[] $parameters The constructor parameters.
     */
    private $parameters;

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_CONSTRUCT;
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
            throw new AnnotationParseException('"construct" annotation content is invalid (invalid JSON content)');
        }
        $this->validate($decoded);

        $index = 0;
        // If there is a custom constructor class
        if (Validator::stringType()->validate($decoded[$index])) {
            $phpFile = RootRetrieverHelper::getRoot($this);
            // If class to use is not from global namespace
            if (! Validator::regex('/^\\\\/')->validate($decoded[$index])) {
                // Add the current namespace to it
                $namespace       = $phpFile->getNamespaceString();
                $decoded[$index] = ($namespace !== null? ($namespace . '\\') : '') . $decoded[$index];
            }
            // Get the last name part
            $nameArray = explode('\\', $decoded[$index]);
            $lastPart  = end($nameArray);
            // Add use to PhpFile
            $phpFile->addConcreteUse($decoded[$index], $lastPart);
            $this->class = $lastPart;
            $index++;
        }
        $this->parameters = $decoded[$index];
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
        if (! Validator::arrayType()->length(1, 2)->validate($decoded)) {
            throw new AnnotationParseException(
                '"construct" annotation content is invalid (must contains parameters array, and maybe a class)'
            );
        }

        $size = count($decoded);

        // Validate that if size is 2, first value is a string
        if ($size === 2 && ! Validator::stringType()->validate($decoded[0])) {
            throw new AnnotationParseException(
                '"construct" annotation content is invalid (constructor class must be a string)'
            );
        }
        // Validate that last value is an array
        if (! Validator::arrayVal()
            ->each(Validator::stringType(), Validator::intType())->validate($decoded[$size - 1])) {
            throw new AnnotationParseException(
                '"construct" annotation content is invalid (constructor parameters must be a array of string)'
            );
        }
    }

    /**
     * @return string|null The class name, null if none.
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @return string[] The constructor parameters.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
