<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\AttributeModel;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserUtil\AttributeVisibilityHelper;

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
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Stmt\Property || ! $parent instanceof TraitModelInterface) {
            throw new Exception('AttributeNodeParser is made to parse a property node');
        }
        $isStatic   = $node->isStatic();
        $visibility = AttributeVisibilityHelper::getPropertyVisibility($node);

        foreach ($node->props as $property) {
            $attribute = new AttributeModel();
            $attribute->setParentNode($parent);
            $attribute->setName($property->name);
            $attribute->setIsStatic($isStatic);
            $attribute->setVisibility($visibility);

            if ($property->default !== null) {
                $this->valueNodeParser->invoke($property->default, $attribute);
            }

            $parent->addAttribute($attribute);
        }
    }
}
