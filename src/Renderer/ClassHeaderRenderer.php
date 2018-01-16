<?php

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Class ClassHeaderRenderer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ClassHeaderRenderer extends AbstractPhpRenderer
{
    /**
     * {@inheritdoc}
     */
    protected $indent = 1;

    public function invoke(PhpFileModelInterface $phpFile): string
    {
        $this->begin();

        foreach ($phpFile->getTraits() as $trait) {
            $this->addAttribute($trait->getName());
        }
        foreach ($phpFile->getClasses() as $class) {
            $this->addAttribute($class->getName());
        }

        return $this->get();
    }

    private function addAttribute(string $name): void
    {
        $this->doc([sprintf('@var %s $instance An instance to use in tests.', $name)])
            ->add(sprintf('private $%s;', lcfirst($name)))->add();
    }
}
