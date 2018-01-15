<?php

namespace PhpUnitGen\Renderer\RendererInterface;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Interface PhpFileRendererInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface PhpFileRendererInterface
{
    /**
     * Use a php file model to render unit tests skeletons.
     *
     * @param PhpFileModelInterface $phpFile The php file model.
     *
     * @return string The rendered unit tests skeleton.
     */
    public function invoke(PhpFileModelInterface $phpFile): string;
}