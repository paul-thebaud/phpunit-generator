<?php

namespace PhpUnitGen\Parser;

use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConsoleConfigInterface;
use PhpUnitGen\Exception\InvalidArgumentException;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\DirectoryModel;
use PhpUnitGen\Model\ModelInterface\DirectoryModelInterface;
use PhpUnitGen\Parser\ParserInterface\DirectoryParserInterface;
use PhpUnitGen\Parser\ParserInterface\PhpFileParserInterface;

/**
 * Class DirectoryParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DirectoryParser implements DirectoryParserInterface
{
    /**
     * @var ConsoleConfigInterface $config The configuration to use.
     */
    private $config;

    /**
     * @var FilesystemInterface $fileSystem The file system to use.
     */
    private $fileSystem;

    /**
     * @var PhpFileParserInterface $phpFileParser The php file parser to use.
     */
    private $phpFileParser;

    /**
     * DirectoryParser constructor.
     *
     * @param ConsoleConfigInterface $config        A config instance.
     * @param FilesystemInterface    $fileSystem    A file system instance.
     * @param PhpFileParserInterface $phpFileParser A php file parser instance.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        FilesystemInterface $fileSystem,
        PhpFileParserInterface $phpFileParser
    ) {
        $this->config        = $config;
        $this->fileSystem    = $fileSystem;
        $this->phpFileParser = $phpFileParser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $sourceDirectory, string $targetDirectory): DirectoryModelInterface
    {
        if (! $this->fileSystem->has($sourceDirectory) || ! $this->fileSystem->getMimetype($sourceDirectory) === 'directory') {
            throw new ParsingException(sprintf('The source directory "%s" does not exist.', $sourceDirectory));
        }

        $directoryModel = new DirectoryModel();
        $directoryModel->setSourceDirectory($sourceDirectory);
        $directoryModel->setTargetDirectory($targetDirectory);

        foreach ($this->fileSystem->listContents($sourceDirectory) as $file) {
            if ($file['type'] === 'file'
                && @preg_match($this->config->getIncludeRegex(), $file['path']) === 1
                && @preg_match($this->config->getExcludeRegex(), $file['path']) === 0
                && ($fileContent = $this->fileSystem->read($file['path'])) !== false
            ) {
                /** @todo Add error on read return false */
                $directoryModel->addPhpFile($this->phpFileParser->parse($fileContent));
            }
        }

        return $directoryModel;
    }
}