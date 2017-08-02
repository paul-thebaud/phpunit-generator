<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Renderer\RendererInterface;

use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;

/**
 * Interface TestRendererInterface
 *
 *      Specifies which methods will contains a TestRenderer
 *      A TestRenderer will create the tests class file content
 *      from a ClassModelInterface
 *
 * @package PHPUnitGenerator\Renderer\RendererInterface
 */
interface TestRendererInterface
{
    /**
     * Generate tests class for a ClassModelInterface
     * and return class code as a string
     *
     * @param ClassModelInterface $classModel
     *
     * @return string
     */
    public function render(ClassModelInterface $classModel): string;
}
