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
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Class MethodVisibilityHelper.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class MethodVisibilityHelper
{
    /**
     * Get the visibility of a method.
     *
     * @param Node\Stmt\ClassMethod $method The method.
     *
     * @return int The visibility method.
     *
     * @see VisibilityInterface For different constants of visibility.
     */
    public static function getVisibility(Node\Stmt\ClassMethod $method): int
    {
        if ($method->isPrivate()) {
            return VisibilityInterface::PRIVATE;
        }
        if ($method->isProtected()) {
            return VisibilityInterface::PROTECTED;
        }
        return VisibilityInterface::PUBLIC;
    }
}
