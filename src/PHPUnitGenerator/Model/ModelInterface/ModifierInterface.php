<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Model\ModelInterface;

/**
 * Interface TypeInterface
 *
 *      Specifies possible types for ClassModelInterface::getModifier
 *      or MethodModelInterface::getModifier as constants
 *
 * @package PHPUnitGenerator\Model\ModelInterface
 */
interface ModifierInterface
{
    /**
     * @var string MODIFIER_NONE None modifier
     */
    const MODIFIER_NONE = '';

    /**
     * @var string MODIFIER_STATIC The modifier "static"
     */
    const MODIFIER_STATIC = 'static';

    /**
     * @var string MODIFIER_FINAL The modifier "final"
     */
    const MODIFIER_FINAL = 'final';

    /**
     * @var string MODIFIER_ABSTRACT The modifier "abstract"
     */
    const MODIFIER_ABSTRACT = 'abstract';
}
