<?php

namespace PhpUnitGen\Model\PropertyInterface;

use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;

/**
 * Interface InPhpFileInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface InPhpFileInterface
{
    /**
     * @param PhpFileModelInterface $phpFileModel The new php file to be set.
     */
    public function setPhpFileModel(PhpFileModelInterface $phpFileModel): void;

    /**
     * @return PhpFileModelInterface The current php file.
     */
    public function getPhpFileModel(): PhpFileModelInterface;
}
