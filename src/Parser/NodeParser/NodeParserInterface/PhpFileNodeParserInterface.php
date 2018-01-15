<?php

namespace PhpUnitGen\Parser\NodeParser\NodeParserInterface;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Interface PhpFileNodeParserInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface PhpFileNodeParserInterface extends NodeParserInterface
{
    /**
     * Pre parse uses in nodes.
     *
     * @param array                 $nodes  The nodes to parse to find uses.
     * @param PhpFileModelInterface $parent The parent to update.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function preParseUses(array $nodes, PhpFileModelInterface $parent): PhpFileModelInterface;
}
