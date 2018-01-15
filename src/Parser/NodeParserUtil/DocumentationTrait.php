<?php

namespace PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Trait DocumentationTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait DocumentationTrait
{
    /**
     * Get the documentation of a node.
     *
     * @param Node $node The node.
     *
     * @return string|null The documentation of the node, null if none.
     */
    protected function getDocumentation(Node $node): ?string
    {
        $phpdoc = $node->getDocComment();
        if ($phpdoc !== null) {
            return $phpdoc->getText();
        }
        return null;
    }
}
