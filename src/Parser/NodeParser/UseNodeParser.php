<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

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
     * @param Node\Stmt\Use_        $node   The node to parse.
     * @param PhpFileModelInterface $parent The parent node.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\Use_ $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        if ($node->type === Node\Stmt\Use_::TYPE_NORMAL) {
            foreach ($node->uses as $use) {
                $parent->addUse($use->alias, $use->name->toString());
            }
        }

        return $parent;
    }
}
