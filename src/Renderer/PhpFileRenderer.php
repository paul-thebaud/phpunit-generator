<?php

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;

/**
 * Class PhpFileRenderer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpFileRenderer extends AbstractPhpRenderer implements PhpFileRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(PhpFileModelInterface $phpFile): string
    {
        return $this->render('class.php', [
            'phpFile' => $phpFile,
            'content' => 'Mon chibre'
        ]);
    }
}
