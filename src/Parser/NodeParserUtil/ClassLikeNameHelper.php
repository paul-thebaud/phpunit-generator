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

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\NameInterface;

/**
 * Class ClassLikeNameHelper.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ClassLikeNameHelper
{
    /**
     * Get the name of a node.
     *
     * @param Node\Stmt\ClassLike $node The node.
     *
     * @return string The found name.
     */
    public static function getName(Node\Stmt\ClassLike $node): string
    {
        if ($node->name === null) {
            return NameInterface::UNKNOWN_NAME;
        }
        return $node->name;
    }
}
