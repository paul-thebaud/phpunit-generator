<?php

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;

/**
 * Class AbstractAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractAnnotation implements AnnotationInterface
{
    use NodeTrait;

    /**
     * @var string $name The annotation name (such as "@PhpUnitGen\AssertTrue()").
     */
    private $name;

    /**
     * @var int $line The annotation line on documentation block.
     */
    private $line;

    /**
     * @var string|null $stringContent The string content of annotation, null if none.
     */
    private $stringContent;

    /**
     * {@inheritdoc}
     */
    abstract public function getType(): int;

    /**
     * {@inheritdoc}
     */
    abstract public function compile(): void;

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setLine(int $line): void
    {
        $this->line = $line;
    }

    /**
     * {@inheritdoc}
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * {@inheritdoc}
     */
    public function setStringContent(?string $stringContent): void
    {
        $this->stringContent = $stringContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getStringContent(): ?string
    {
        return $this->stringContent;
    }
}
