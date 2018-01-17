<?php

namespace PhpUnitGen\Validator;

use League\Flysystem\FilesystemInterface;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\FileNotFoundException;
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
        if (($type = $this->getPathType($path)) === null) {
            throw new FileNotFoundException(sprintf('The source file "%s" does not exist.', $path));
        }
        if ($type === 'dir') {
            return false;
        }
        if (! $this->validateIncludeRegex($path)
            || ! $this->validateExcludeRegex($path)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Validate file has a valid path.
     *
     * @param string $path The file path.
     *
     * @return string|null "file" if its a file, "dir" if its a dir and null if the path does not exists.
     */
    private function getPathType(string $path): ?string
    {
        return $this->fileSystem->has($path) && $this->fileSystem->get($path)->getType() === 'file';
    }

    /**
     * Validate file has the include regex if this regex is set.
     *
     * @param string $path The file path.
     *
     * @return bool True if it pass this validation.
     */
    private function validateIncludeRegex(string $path): bool
    {
        $includeRegex = $this->config->getIncludeRegex();
        return $includeRegex === null || Validator::regex($includeRegex)->validate($path) === true;
    }

    /**
     * Validate file has not the exclude regex if this regex is set.
     *
     * @param string $path The file path.
     *
     * @return bool True if it pass this validation.
     */
    private function validateExcludeRegex(string $path): bool
    {
        $excludeRegex = $this->config->getExcludeRegex();
        return $excludeRegex === null || Validator::regex($excludeRegex)->validate($path) !== true;
    }
}
