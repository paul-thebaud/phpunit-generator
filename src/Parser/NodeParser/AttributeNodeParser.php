<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\AttributeModel;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
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
class AttributeNodeParser extends AbstractNodeParser
{
    use AttributeVisibilityTrait;

    /**
     * @var ValueNodeParser $valueNodeParser The value node parser.
     */
    protected $valueNodeParser;

    /**
     * AttributeNodeParser constructor.
     *
     * @param ValueNodeParser $valueNodeParser The value node parser.
     */
    public function __construct(ValueNodeParser $valueNodeParser)
    {
        $this->valueNodeParser = $valueNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\Property  $node   The node to parse.
     * @param TraitModelInterface $parent The parent node.
     *
     * @return TraitModelInterface The updated parent.
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
