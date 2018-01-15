<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\AttributeModel;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

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
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\Property  $node   The property node to parse.
         * @var TraitModelInterface $parent The node which contains this namespace.
         */
        foreach ($node->props as $property) {
            $attribute = new AttributeModel();
            $attribute->setName($property->name);
            $attribute->setIsStatic($node->isStatic());
            $attribute->setVisibility($this->retrieveVisibility($node));

            $parent->addAttribute($attribute);
        }

        return $parent;
    }

    /**
     * Retrieve the visibility of this property.
     *
     * @param Node\Stmt\Property $property The property.
     *
     * @return int The visibility as an integer.
     */
    private function retrieveVisibility(Node\Stmt\Property $property): int
    {
        if ($property->isPrivate()) {
            return VisibilityInterface::PRIVATE;
        }
        if ($property->isProtected()) {
            return VisibilityInterface::PROTECTED;
        }
        return VisibilityInterface::PUBLIC;
    }
}
