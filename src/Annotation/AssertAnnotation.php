<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Util\Json;

/**
 * Class AssertAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AssertAnnotation extends AbstractAnnotation
{
    /**
     * @var string|null $expected The expected value, null if none.
     */
    private $expected;

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_ASSERT;
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
                throw new AnnotationParseException('"assertion" annotation content is invalid (invalid JSON content)');
            }
            if (! is_string($decoded)) {
                throw new AnnotationParseException(
                    '"assertion" annotation content is invalid (expected value must be a string)'
                );
            }
            $this->expected = $decoded;
        }
    }

    /**
     * @return string The expected value, null if none.
     */
    public function getExpected(): ?string
    {
        return $this->expected;
    }
}
