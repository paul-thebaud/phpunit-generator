<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Renderer;

use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Renderer\RendererInterface\TestRendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigTestRenderer
 *
 *      An implementation of TestRendererInterface using Twig renderer
 *
 * @see     https://github.com/twigphp/Twig
 *
 * @package PHPUnitGenerator\Renderer
 */
class TwigTestRenderer implements TestRendererInterface
{
    /**
     * @var string DEFAULT_TEMPLATE_FOLDER The default template folder
     */
    const DEFAULT_TEMPLATE_FOLDER = __DIR__ . '/../../../template';

    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * @var \Twig\Environment $twig The template renderer
     */
    private $twig;

    /**
     * TwigTestRenderer constructor
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;

        // Create the FileSystemLoader
        $templateFolder = $this->config->getOption(ConfigInterface::OPTION_TWIG_TEMPLATE_FOLDER) ??
            self::DEFAULT_TEMPLATE_FOLDER;
        $loader = new FilesystemLoader($templateFolder);

        // Create twig configuration
        $twigConfig['cache'] = false;
        $twigConfig['debug'] = false;
        if ($this->config->getOption(ConfigInterface::OPTION_TWIG_DEBUG) === true) {
            $twigConfig['debug'] = true;
        }

        // Create Twig renderer environment
        $this->twig = new Environment($loader, $twigConfig);

        // Add method lcfirst
        $this->twig->addFilter(new \Twig_SimpleFilter('lcfirst', 'lcfirst'));

        if ($twigConfig['debug'] === true) {
            $this->twig->addExtension(new \Twig_Extension_Debug());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(ClassModelInterface $classModel): string
    {
        return $this->twig->render('class.twig', [
            'class' => $classModel
        ]);
    }
}
