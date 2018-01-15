<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\AttributeModel;
use PhpUnitGen\Model\ModelInterface\TraitModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserTrait\VisibilityTrait;
use Respect\Validation\Validator;

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
    use VisibilityTrait;

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
        if (! Validator::instance(Node\Stmt\Property::class)->validate($node)) {
            return $parent;
        }

        $isStatic = $node->isStatic();
        $visibility = $this->parseVisibility($node);

        foreach ($node->props as $property) {
            $attribute = new AttributeModel();
            $attribute->setName($property->name);
            $attribute->setIsStatic($isStatic);
            $attribute->setVisibility($visibility);

            /** @todo */
            $attribute->setValue(null);

            $parent->addAttribute($attribute);
        }

        return $parent;
    }
}
