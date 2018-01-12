<?php

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\DirectoryModelInterface;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;

/**
 * Class DirectoryModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DirectoryModel implements DirectoryModelInterface
{
    use NodeTrait;

    /**
     * @var string $sourceDirectory The source directory (contains php files to parse).
     */
    private $sourceDirectory;

    /**
     * @var string $targetDirectory The target directory (will contains generated tests files).
     */
    private $targetDirectory;

    /**
     * @var PhpFileModel[] $phpFiles Php files contained in the directory (and sub directories).
     */
    private $phpFiles = [];

    /**
     * {@inheritdoc}
     */
    public function setSourceDirectory(string $sourceDirectory): void
    {
        $this->sourceDirectory = $sourceDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceDirectory(): string
    {
        return $this->sourceDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetDirectory(string $targetDirectory): void
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function addPhpFile(PhpFileModelInterface $phpFile): void
    {
        $this->phpFiles[] = $phpFile;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpFiles(): array
    {
        return $this->phpFiles;
    }
}