<?php

namespace PhpUnitGen\Validator;

use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\NotReadableFileException;
use PhpUnitGen\Validator\ValidatorInterface\FileValidatorInterface;
use Respect\Validation\Validator;

/**
 * Class FileValidator.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class FileValidator implements FileValidatorInterface
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
     * FileValidator constructor.
     *
     * @param ConsoleConfigInterface $config     The config to use.
     * @param FilesystemInterface    $filesystem The file system to use.
     */
    public function __construct(ConsoleConfigInterface $config, FilesystemInterface $filesystem)
    {
        $this->config     = $config;
        $this->fileSystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(string $path): bool
    {
        if (! $this->fileSystem->has($path)
            || $this->fileSystem->get($path)->getType() !== 'file') {
            return false;
        }

        // Nullable regex
        $includeRegex = $this->config->getIncludeRegex();
        $excludeRegex = $this->config->getExcludeRegex();
        if (($includeRegex !== null && $excludeRegex !== null)
            && (
                Validator::regex($includeRegex)->validate($path) !== true
                || Validator::regex($excludeRegex)->validate($path) === true
            )
        ) {
            return false;
        }

        // Not readable file
        if ($this->fileSystem->getVisibility($path) === AdapterInterface::VISIBILITY_PRIVATE) {
            throw new NotReadableFileException(sprintf('The file "%s" is not readable.', $path));
        }

        return true;
    }
}
