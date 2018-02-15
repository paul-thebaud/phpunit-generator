<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Renderer\Helper\ParametersHelper;
use PhpUnitGen\Renderer\Helper\ValueHelper;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;
use Slim\Views\PhpRenderer;

/**
 * Class PhpFileRenderer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpFileRenderer implements PhpFileRendererInterface
{
    /**
     * @var PhpRenderer $renderer The twig renderer.
     */
    protected $renderer;

    /**
     * AbstractRender constructor.
     *
     * @param ConfigInterface $config   The configuration.
     * @param PhpRenderer     $renderer The php renderer.
     */
    public function __construct(
        ConfigInterface $config,
        PhpRenderer $renderer
    ) {
        $this->renderer = $renderer;
        $this->renderer->addAttribute('config', $config);
        $this->renderer->addAttribute('parametersHelper', new ParametersHelper());
        $this->renderer->addAttribute('valueHelper', new ValueHelper());
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(PhpFileModelInterface $phpFile): string
    {
        return $this->renderer->fetch('class.php', [
            'phpFile' => $phpFile
        ]);
    }
}
