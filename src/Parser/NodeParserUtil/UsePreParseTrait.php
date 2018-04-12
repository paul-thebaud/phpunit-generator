<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParser\GroupUseNodeParser;
use PhpUnitGen\Parser\NodeParser\UseNodeParser;

/**
 * Trait UsePreParseTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait UsePreParseTrait
{
    /**
     * @var UseNodeParser $useNodeParser The use node parser.
     */
    protected $useNodeParser;

    /**
     * @var GroupUseNodeParser $groupUseNodeParser The group use node parser.
     */
    protected $groupUseNodeParser;

    /**
     * Pre parse uses in nodes.
     *
     * @param array                 $nodes  The nodes to parse to find uses.
     * @param PhpFileModelInterface $parent The parent to update.
     */
    public function preParseUses(array $nodes, PhpFileModelInterface $parent): void
    {
        foreach ($nodes as $node) {
            if ($node instanceof Use_) {
                $this->useNodeParser->invoke($node, $parent);
            } else {
                if ($node instanceof GroupUse) {
                    $this->groupUseNodeParser->invoke($node, $parent);
                }
            }
        }
    }
}
