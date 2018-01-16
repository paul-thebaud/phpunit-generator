<?php

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Model\ModelInterface\InterfaceModelInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Class ClassLikeRenderer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ClassLikeRenderer extends AbstractPhpRenderer
{
    private $functionRenderer;

    protected $indent = 1;

    public function __construct(ConfigInterface $config, FunctionRenderer $functionRenderer)
    {
        parent::__construct($config);
        $this->functionRenderer = $functionRenderer;
    }

    public function invoke(InterfaceModelInterface $interface): string
    {
        $this->begin();

        foreach ($interface->getFunctions() as $function) {
            $this->concat($this->functionRenderer->invoke($function));
        }

        return $this->get();
    }
}
