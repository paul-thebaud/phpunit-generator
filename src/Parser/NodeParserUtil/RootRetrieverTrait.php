<?php

namespace PhpUnitGen\Parser\NodeParserUtil;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use Respect\Validation\Validator;

/**
 * Trait RootRetrieverTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait RootRetrieverTrait
{
    /**
     * Get the root of the node.
     *
     * @param NodeInterface $node The node to search on.
     *
     * @return PhpFileModelInterface|null The root if it is found, else null.
     */
    public function getRoot(NodeInterface $node): ?PhpFileModelInterface
    {
        $parent = $node->getParentNode();
        while ($parent !== null) {
            if (Validator::instance(PhpFileModelInterface::class)->validate($parent)) {
                return $parent;
            }
            $parent = $this->getRoot($parent);
        }
        return null;
    }
}
