<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model\ModelInterface;

use PhpUnitGen\Annotation\ConstructAnnotation;
use PhpUnitGen\Model\PropertyInterface\ClassLikeInterface;
use PhpUnitGen\Model\PropertyInterface\NameInterface;

/**
 * Interface InterfaceModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface InterfaceModelInterface extends NameInterface, ClassLikeInterface
{
    /**
     * @return ConstructAnnotation|null The construct annotation, null if none.
     */
    public function getConstructAnnotation(): ?ConstructAnnotation;
}
