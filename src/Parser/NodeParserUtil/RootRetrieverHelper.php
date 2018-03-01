<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\NodeParserUtil;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class RootRetrieverHelper.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class RootRetrieverHelper
{
    /**
     * Get the root of the node.
     *
     * @param NodeInterface $parent The node to search in.
     *
     * @return PhpFileModelInterface|null The root if it is found, else null.
     */
    public static function getRoot(NodeInterface $parent): ?PhpFileModelInterface
    {
        while ($parent !== null) {
            if ($parent instanceof PhpFileModelInterface) {
                return $parent;
            }
            $parent = $parent->getParentNode();
        }
        return null;
    }
}
