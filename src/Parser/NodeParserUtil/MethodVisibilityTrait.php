<?php

namespace PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Trait MethodVisibilityTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait MethodVisibilityTrait
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
    public function getMethodVisibility(Node\Stmt\ClassMethod $method): int
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
