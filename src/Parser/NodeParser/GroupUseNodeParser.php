<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class GroupUseNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class GroupUseNodeParser extends AbstractNodeParser
{
    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Node\Stmt\GroupUse || ! $parent instanceof PhpFileModelInterface) {
            throw new Exception('GroupUseNodeParser is made to parse a use group node');
        }

        if ($this->validateType($node->type)) {
            $prefix = $node->prefix->toString();
            foreach ($node->uses as $use) {
                if ($this->validateType($node->type)) {
                    $parent->addUse($use->alias, $prefix . '\\' . $use->name->toString());
                }
            }
        }
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
