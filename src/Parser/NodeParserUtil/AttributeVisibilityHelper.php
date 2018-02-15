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
 * Class AttributeVisibilityHelper.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class AttributeVisibilityHelper
{
    /**
     * Get the visibility of a property.
     *
     * @param Node\Stmt\Property $property The property.
     *
     * @return int The visibility property.
     *
     * @see VisibilityInterface For different constants of visibility.
     */
    public static function getVisibility(Node\Stmt\Property $property): int
    {
        if ($property->isPrivate()) {
            return VisibilityInterface::PRIVATE;
        }
        if ($property->isProtected()) {
            return VisibilityInterface::PROTECTED;
        }
        return VisibilityInterface::PUBLIC;
    }
}
