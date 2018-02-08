<?php

namespace PhpUnitGen\Annotation\AnnotationInterface;

use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Interface AnnotationInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface AnnotationInterface extends NodeInterface
{
    /**
     * @var int TYPE_ASSERT The annotation type "assert", such as "@PhpUnitGen\assertTrue()".
     */
    public const TYPE_ASSERT = 0;

    /**
     * @var int TYPE_GET The annotation type "get", such as "@PhpUnitGen\get()".
     */
    public const TYPE_GET = 1;

    /**
     * @var int TYPE_SET The annotation type "set", such as "@PhpUnitGen\set()".
     */
    public const TYPE_SET = 2;

    /**
     * @var int TYPE_MOCK The annotation type "mock", such as "@PhpUnitGen\mock(MyObject)".
     */
    public const TYPE_MOCK = 3;

    /**
     * @var int TYPE_CONSTRUCT The annotation type "construct", such as "@PhpUnitGen\construct([])".
     */
    public const TYPE_CONSTRUCT = 4;

    /**
     * @var int TYPE_PARAMS The annotation type "params", such as "@PhpUnitGen\params()".
     */
    public const TYPE_PARAMS = 5;

    /**
     * @return int The annotation type.
     */
    public function getType(): int;

    /**
     * Compile the annotation name and content to fill required properties.
     *
     * @throws AnnotationParseException If the annotation content is invalid.
     */
    public function compile(): void;

    /**
     * @param string $name Set the annotation name (such as "@PhpUnitGen\Getter").
     */
    public function setName(string $name): void;

    /**
     * @return string The annotation name (such as "@PhpUnitGen\AssertTrue()").
     */
    public function getName(): string;

    /**
     * @param int $line Set the annotation line on documentation block.
     */
    public function setLine(int $line): void;

    /**
     * @return int The annotation line on documentation block.
     */
    public function getLine(): int;

    /**
     * @param string|null $stringContent Set the string content of annotation, null if none.
     */
    public function setStringContent(?string $stringContent): void;

    /**
     * @return string|null The string content of annotation, null if none.
     */
    public function getStringContent(): ?string;
}
