<?php

namespace PhpUnitGen\Parser;

use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConsoleConfigInterface;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\DirectoryModel;
use PhpUnitGen\Model\ModelInterface\DirectoryModelInterface;
use PhpUnitGen\Parser\ParserInterface\DirectoryParserInterface;
use PhpUnitGen\Parser\ParserInterface\PhpFileParserInterface;
use Respect\Validation\Validator;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var OutputInterface $output The output to display message.
     */
    private $output;

    /**
     * DirectoryParser constructor.
     *
     * @param ConsoleConfigInterface $config        A config instance.
     * @param FilesystemInterface    $fileSystem    A file system instance.
     * @param PhpFileParserInterface $phpFileParser A php file parser instance.
     * @param OutputInterface        $output        The output to display message.
     */
    public function __construct(
        ConsoleConfigInterface $config,
        FilesystemInterface $fileSystem,
        PhpFileParserInterface $phpFileParser,
        OutputInterface $output
    ) {
        $this->config        = $config;
        $this->fileSystem    = $fileSystem;
        $this->phpFileParser = $phpFileParser;
        $this->output        = $output;
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

        $directoryModel = $this->addPhpFiles($directoryModel);

        return $directoryModel;
    }

    /**
     * Get all php files from a directory (and sub-directories).
     *
     * @param DirectoryModelInterface $directoryModel The directory model to save php files in.
     *
     * @return DirectoryModelInterface The updated directory model.
     *
     * @throws ParsingException If the file is not readable.
     */
    private function addPhpFiles(DirectoryModelInterface $directoryModel): DirectoryModelInterface
    {
        foreach ($this->fileSystem->listContents($directoryModel->getSourceDirectory(), true) as $file) {
            try {
                if ($this->validateFile($file['type'], $file['path'])) {
                    $phpFile = $this->phpFileParser->parse($this->fileSystem->read($file['path']));

                    $phpFile->setName($file['filename']);
                    $phpFile->setPath($file['dirname']);
                    $phpFile->setParentNode($directoryModel);

                    $directoryModel->addPhpFile($phpFile);

                    $this->output->writeln(sprintf('<comment>Parsing file "%s" completed.</comment>', $file['path']));
                }
            } catch (ParsingException $exception) {
                if (! $this->config->hasIgnore()) {
                    throw new ParsingException($exception->getMessage());
                }
                $this->output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
            }
        }

        return $directoryModel;
    }

    /**
     * Get a file content if the file match requirements and if it is readable.
     *
     * @param string $type The file type.
     * @param string $path The file path.
     *
     * @return bool If the file is valid.
     *
     * @throws ParsingException If the file is not readable.
     */
    private function validateFile(string $type, string $path): bool
    {
        if ($type !== 'file'
            || Validator::regex($this->config->getIncludeRegex())->validate($path) !== true
            || Validator::regex($this->config->getExcludeRegex())->validate($path) === true
        ) {
            return false;
        }
        if ($this->fileSystem->getVisibility($path) === AdapterInterface::VISIBILITY_PRIVATE) {
            throw new ParsingException(sprintf('The file "%s" is not readable.', $path));
        }
        return true;
    }
}