<?php

namespace PhpUnitGen\Renderer;

use Slim\Views\PhpRenderer;

/**
 * Class AbstractRendererInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractPhpRenderer
{
    /**
     * @var PhpRenderer $renderer The php renderer from slim framework.
     */
    private $renderer;

    /**
     * AbstractRendererInterface constructor.
     *
     * @param PhpRenderer $renderer The php renderer.
     */
    public function __construct(PhpRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Render a view and merge view data.
     *
     * @param string $view The view to render.
     * @param array  $data The data to add.
     *
     * @return string The render result.
     */
    protected function render(string $view, array $data): string
    {
        $this->renderer->setAttributes(array_merge($this->renderer->getAttributes(), $data));
        return $this->renderer->fetch($view);
    }
}
