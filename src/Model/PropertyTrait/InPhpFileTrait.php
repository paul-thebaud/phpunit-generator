<?php

namespace PhpUnitGen\Model\PropertyTrait;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Trait InPhpFileTrait.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
trait InPhpFileTrait
{
    /**
     * @var PhpFileModelInterface $phpFileModel A php file.
     */
    protected $phpFileModel;

    /**
     * @param PhpFileModelInterface $phpFileModel The new php file to be set.
     */
    public function setPhpFileModel(PhpFileModelInterface $phpFileModel): void
    {
        $this->phpFileModel = $phpFileModel;
    }

    /**
     * @return PhpFileModelInterface The current php file.
     */
    public function getPhpFileModel(): PhpFileModelInterface
    {
        return $this->phpFileModel;
    }
}
