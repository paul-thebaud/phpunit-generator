<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use Respect\Validation\Validator;

/**
 * Class UseNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class UseNodeParser extends AbstractNodeParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Node $node, NodeInterface $parent): NodeInterface
    {
        /**
         * Overriding variable types.
         * @var Node\Stmt\Use_        $node   The namespace node to parse.
         * @var PhpFileModelInterface $parent The node which contains this namespace.
         */
        if (! Validator::instance(Node\Stmt\Use_::class)->validate($node)) {
            return $parent;
        }
        foreach ($node->uses as $use) {
            $parent->addUse($use->alias, $use->name->toString());
        }

        return $parent;
    }
}
