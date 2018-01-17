<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\AttributeModel;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\AttributeNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\ValueNodeParserInterface;
use PhpUnitGen\Parser\NodeParserUtil\AttributeVisibilityTrait;

/**
 * Class AttributeNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AttributeNodeParser extends AbstractNodeParser implements AttributeNodeParserInterface
{
    use AttributeVisibilityTrait;

    /**
     * @var ValueNodeParserInterface $valueNodeParser The value node parser.
     */
    protected $valueNodeParser;

    /**
     * AttributeNodeParser constructor.
     *
     * @param ValueNodeParserInterface $valueNodeParser The value node parser.
     */
    public function __construct(ValueNodeParserInterface $valueNodeParser)
    {
        $this->valueNodeParser = $valueNodeParser;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(Node\Stmt\Property $node, TraitModelInterface $parent): TraitModelInterface
    {
        $isStatic   = $node->isStatic();
        $visibility = $this->getPropertyVisibility($node);

        foreach ($node->props as $property) {
            $attribute = new AttributeModel();
            $attribute->setParentNode($parent);
            $attribute->setName($property->name);
            $attribute->setIsStatic($isStatic);
            $attribute->setVisibility($visibility);

            if ($property->default !== null) {
                $attribute = $this->valueNodeParser->invoke($property->default, $attribute);
            }

            $parent->addAttribute($attribute);
        }

        return $parent;
    }
}
