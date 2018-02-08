<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Util\Json;
use Respect\Validation\Validator;

/**
 * Class ParamsAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ParamsAnnotation extends AbstractAnnotation
{
    /**
     * @var string[] $parameters The method call parameters.
     */
    private $parameters;

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_PARAMS;
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
            throw new AnnotationParseException('"params" annotation content is invalid (invalid JSON content)');
        }
        if (! Validator::arrayVal()->each(Validator::stringType(), Validator::intType())->validate($decoded)) {
            throw new AnnotationParseException('"params" annotation content is invalid (must contains strings only)');
        }
        $this->parameters = $decoded;
    }

    /**
     * @return string[] The constructor parameters.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
