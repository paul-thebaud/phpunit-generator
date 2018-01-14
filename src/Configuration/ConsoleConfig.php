<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\InvalidConfigException;
use Respect\Validation\Validator;

/**
 * Class ConsoleConfig.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ConsoleConfig extends BaseConfig implements ConsoleConfigInterface
{
    /**
     * {@inheritdoc}
     */
    protected function validate($config): void
    {
        parent::validate($config);

        $this->validateBooleans($config);
        $this->validateStrings($config);
        $this->validateDirsAndFiles($config);
    }

    /**
     * Validate all boolean attributes contained in configuration.
     *
     * @param mixed $config The configuration.
     *
     * @throws InvalidConfigException If a boolean attribute is invalid.
     */
    private function validateBooleans($config): void
    {
        // Check boolean parameters
        if (! Validator::key('overwrite', Validator::boolType())->validate($config)) {
            throw new InvalidConfigException('"overwrite" parameter must be set as a boolean.');
        }
        if (! Validator::key('auto', Validator::boolType())->validate($config)) {
            throw new InvalidConfigException('"auto" parameter must be set as a boolean.');
        }
        if (! Validator::key('ignore', Validator::boolType())->validate($config)) {
            throw new InvalidConfigException('"ignore" parameter must be set as a boolean.');
        }
    }

    /**
     * Validate all string attributes contained in configuration.
     *
     * @param mixed $config The configuration.
     *
     * @throws InvalidConfigException If a string attribute is invalid.
     */
    private function validateStrings($config): void
    {
        // Check string parameters
        if (! Validator::key('include', Validator::stringType())->validate($config)) {
            throw new InvalidConfigException('"include" parameter must be set as a string.');
        }
        if (! Validator::key('exclude', Validator::stringType())->validate($config)) {
            throw new InvalidConfigException('"exclude" parameter must be set as a string.');
        }
    }

    /**
     * Validate directories and files contained in configuration.
     *
     * @param mixed $config The configuration.
     *
     * @throws InvalidConfigException If a directory is invalid (source or target).
     */
    private function validateDirsAndFiles($config): void
    {
        // Check that dirs exists
        if (! Validator::key('dirs', Validator::arrayType())->validate($config)) {
            throw new InvalidConfigException('"dirs" parameter is not an array.');
        }
        if (! Validator::key('files', Validator::arrayType())->validate($config)) {
            throw new InvalidConfigException('"files" parameter is not an array.');
        }
        // Validate each dirs
        if (! Validator::arrayVal()
            ->each(Validator::stringType(), Validator::stringType())->validate($config['dirs'])
        ) {
            throw new InvalidConfigException('Some directories in "dirs" parameter are not strings.');
        }
        // Validate each dirs
        if (! Validator::arrayVal()
            ->each(Validator::stringType(), Validator::stringType())->validate($config['files'])
        ) {
            throw new InvalidConfigException('Some files in "files" parameter are not strings.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasOverwrite(): bool
    {
        return $this->config['overwrite'];
    }

    /**
     * {@inheritdoc}
     */
    public function hasIgnore(): bool
    {
        return $this->config['ignore'];
    }

    /**
     * {@inheritdoc}
     */
    public function getIncludeRegex(): string
    {
        return $this->config['include'];
    }

    /**
     * {@inheritdoc}
     */
    public function getExcludeRegex(): string
    {
        return $this->config['exclude'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectories(): array
    {
        return $this->config['dirs'];
    }

    /**
     * {@inheritdoc}
     */
    public function addDirectory(string $sourceDirectory, string $targetDirectory): void
    {
        if (! Validator::arrayType()->validate($this->config['dirs'])) {
            $this->config['dirs'] = [];
        }
        $this->config['dirs'][$sourceDirectory] = $targetDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles(): array
    {
        return $this->config['files'];
    }

    /**
     * {@inheritdoc}
     */
    public function addFile(string $sourceFile, string $targetFile): void
    {
        if (! Validator::arrayType()->validate($this->config['files'])) {
            $this->config['files'] = [];
        }
        $this->config['files'][$sourceFile] = $targetFile;
    }
}
