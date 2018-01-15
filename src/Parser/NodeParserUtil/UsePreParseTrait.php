<?php

namespace PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\GroupUseNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\UseNodeParserInterface;
use Respect\Validation\Validator;

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
     * @var UseNodeParserInterface $useNodeParser The use node parser.
     */
    protected $useNodeParser;

    /**
     * @var GroupUseNodeParserInterface $groupUseNodeParser The group use node parser.
     */
    protected $groupUseNodeParser;

    /**
     * Pre parse uses in nodes.
     *
     * @param array                 $nodes  The nodes to parse to find uses.
     * @param PhpFileModelInterface $parent The parent to update.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function preParseUses(array $nodes, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        foreach ($nodes as $node) {
            if (Validator::instance(Use_::class)->validate($node)) {
                $parent = $this->useNodeParser->invoke($node, $parent);
            }
            if (Validator::instance(GroupUse::class)->validate($node)) {
                $parent = $this->groupUseNodeParser->invoke($node, $parent);
            }
        }
        return $parent;
    }
}
