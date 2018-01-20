<?php

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NameInterface;
use PhpUnitGen\Util\RootRetrieverTrait;

/**
 * Class FunctionRenderer.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FunctionRenderer extends AbstractPhpRenderer
{
    use RootRetrieverTrait;

    protected $indent = 1;

    public function invoke(FunctionModelInterface $function): string
    {
        $this->begin();

        $parent = $function->getParentNode();
        /** @var PhpFileModelInterface $phpFile */
        $phpFile = $this->getRoot($parent);

        if ($phpFile === $parent) {
            $this->doc([sprintf('Covers global method "%s"', $function->getName())]);
        } else {
            /** @var NameInterface $parent */
            $namespace = $phpFile->getFullnameFor($parent->getName());
            $this->doc([
                sprintf(
                    '@covers \\%s::%s',
                    $namespace,
                    $function->getName()
                )
            ]);
        }
        $this->add(
            sprintf('public function test%s()', ucfirst($function->getName()))
        )->add('{')->add('// Some content')->add('}')->add();

        return $this->get();
    }
}
