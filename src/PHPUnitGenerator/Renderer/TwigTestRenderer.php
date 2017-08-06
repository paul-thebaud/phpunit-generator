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
     * @var \Twig\Environment $twig The template renderer
     */
    private $twig;

    /**
     * TwigTestRenderer constructor
     */
    public function __construct()
    {
        // Create the FileSystemLoader
        $loader = $this->getFilesystemLoader(self::DEFAULT_TEMPLATE_FOLDER);

        // Create twig configuration
        $twigConfig['cache'] = false;
        $twigConfig['debug'] = false;

        // Create Twig renderer environment
        $this->twig = new Environment($loader, [
            'cache' => false,
            'debug' => false
        ]);

        // Add method lcfirst
        $this->twig->addFilter(new \Twig_SimpleFilter('lcfirst', 'lcfirst'));
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

    /**
     * Construct a new instance of FilesystemLoader
     *
     * @param array|string $path
     *
     * @return FilesystemLoader
     */
    protected function getFilesystemLoader($path): FilesystemLoader
    {
        return new FilesystemLoader($path);
    }
}
