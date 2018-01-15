<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\GroupUseNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\UseNodeParserInterface;

/**
 * Class GroupUseNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GroupUseNodeParser extends AbstractNodeParser implements GroupUseNodeParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(Node\Stmt\GroupUse $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        if ($this->validateType($node->type)) {
            $prefix = $node->prefix->toString();
            foreach ($node->uses as $use) {
                if ($this->validateType($node->type)) {
                    $parent->addUse($use->alias, $prefix . '\\' . $use->name->toString());
                }
            }
        }

        return $parent;
    }

    /**
     * Validate a use type.
     *
     * @param int $type The type to validate.
     *
     * @return bool True if the type is valid.
     */
    private function validateType(int $type): bool
    {
        return $type === Node\Stmt\Use_::TYPE_NORMAL || $type === Node\Stmt\Use_::TYPE_UNKNOWN;
    }
}
