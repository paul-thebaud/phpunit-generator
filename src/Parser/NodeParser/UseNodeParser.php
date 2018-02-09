<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

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
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Stmt\Use_ || ! $parent instanceof PhpFileModelInterface) {
            throw new Exception('UseNodeParser is made to parse a use node');
        }

        if ($node->type === Node\Stmt\Use_::TYPE_NORMAL) {
            foreach ($node->uses as $use) {
                $parent->addUse($use->alias, $use->name->toString());
            }
        }
    }
}
