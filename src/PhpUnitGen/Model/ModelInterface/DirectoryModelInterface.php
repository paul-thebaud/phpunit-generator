<?php

namespace PhpUnitGen\Model\ModelInterface;

/**
 * Interface DirectoryModelInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface DirectoryModelInterface
{
    /**
     * @param string $sourceDirectory The new source directory to use.
     */
    public function setSourceDirectory(string $sourceDirectory): void;

    /**
     * @return string The source directory (contains php files to parse).
     */
    public function getSourceDirectory(): string;

    /**
     * @param string $targetDirectory The new target directory to use.
     */
    public function setTargetDirectory(string $targetDirectory): void;

    /**
     * @return string The target directory (will contains generated tests skeletons).
     */
    public function getTargetDirectory(): string;

    /**
     * @param PhpFileModelInterface $phpFile A new php file to add.
     */
    public function addPhpFile(PhpFileModelInterface $phpFile): void;

    /**
     * @return PhpFileModelInterface[] All php files contained in source directory and sub-directories.
     */
    public function getPhpFiles(): array;
}
