<?php

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
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
     * @var ClassHeaderRenderer $classHeaderRenderer The class header renderer.
     */
    private $classHeaderRenderer;

    /**
     * @var ClassLikeRenderer $classLikeRenderer The interface renderer.
     */
    private $classLikeRenderer;

    private $functionRenderer;

    /**
     * {@inheritdoc}
     * @param ClassHeaderRenderer $classHeaderRenderer The class header renderer.
     * @param ClassLikeRenderer   $classLikeRenderer   The interface renderer.
     */
    public function __construct(
        ConfigInterface $config,
        ClassHeaderRenderer $classHeaderRenderer,
        ClassLikeRenderer $classLikeRenderer,
        FunctionRenderer $functionRenderer
    ) {
        parent::__construct($config);

        $this->classHeaderRenderer = $classHeaderRenderer;
        $this->classLikeRenderer   = $classLikeRenderer;
        $this->functionRenderer    = $functionRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(PhpFileModelInterface $phpFile): string
    {
        // Php tag
        $this->begin()->add('<?php')->add();

        // Namespace
        $namespace = $phpFile->getNamespaceString();
        $this->add(sprintf('namespace Test%s;', ($namespace === null? '' : '\\' . $namespace)))->add();

        // Uses
        $this->multiple($this->getUseLines($phpFile))->add();

        // Phpdoc
        $this->doc($this->getClassDoc($phpFile));

        // Class
        $this->add(sprintf('class %s extends TestCase', $phpFile->getName()));

        // Content
        $this->add('{')->addContent($phpFile)->add('}');

        return $this->get();
    }

    /**
     * Get php use statements.
     *
     * @param PhpFileModelInterface $phpFile The php file to use.
     *
     * @return array The use statements as an array.
     */
    private function getUseLines(PhpFileModelInterface $phpFile): array
    {
        $lines = ['use PHPUnit\Framework\TestCase;'];
        foreach ($phpFile->getConcreteUses() as $use => $name) {
            // Get the last name part
            $nameArray = explode('\\', $use);
            $lastPart  = end($nameArray);
            if ($lastPart === $name) {
                $lines[] = sprintf('use %s;', $use);
            } else {
                $lines[] = sprintf('use %s as %s;', $use, $name);
            }
        }
        return $lines;
    }

    /**
     * Get test class phpdoc statements.
     *
     * @param PhpFileModelInterface $phpFile The php file to use.
     *
     * @return array The phpdoc lines as an array.
     */
    private function getClassDoc(PhpFileModelInterface $phpFile): array
    {
        $lines = [sprintf('Class %s.', $phpFile->getName()), ''];
        foreach ($this->config->getPhpDoc() as $annotation => $content) {
            $lines[] = sprintf('@%s %s', $annotation, $content);
        }
        $lines[] = '';

        foreach ($phpFile->getInterfaces() as $elem) {
            $lines[] = sprintf('@covers \\%s', $phpFile->getFullNameFor($elem->getName()));
        }
        foreach ($phpFile->getTraits() as $elem) {
            $lines[] = sprintf('@covers \\%s', $phpFile->getFullNameFor($elem->getName()));
        }
        foreach ($phpFile->getClasses() as $elem) {
            $lines[] = sprintf('@covers \\%s', $phpFile->getFullNameFor($elem->getName()));
        }
        return $lines;
    }

    /**
     * Add the class content.
     *
     * @param PhpFileModelInterface $phpFile The php file to use.
     *
     * @return AbstractPhpRenderer $this.
     */
    private function addContent(PhpFileModelInterface $phpFile): AbstractPhpRenderer
    {
        $this->concat($this->classHeaderRenderer->invoke($phpFile));

        foreach ($phpFile->getFunctions() as $function) {
            $this->concat($this->functionRenderer->invoke($function));
        }
        foreach ($phpFile->getInterfaces() as $interface) {
            $this->concat($this->classLikeRenderer->invoke($interface));
        }
        foreach ($phpFile->getTraits() as $trait) {
            $this->concat($this->classLikeRenderer->invoke($trait));
        }
        foreach ($phpFile->getClasses() as $class) {
            $this->concat($this->classLikeRenderer->invoke($class));
        }

        $this->remove();

        return $this;
    }
}
