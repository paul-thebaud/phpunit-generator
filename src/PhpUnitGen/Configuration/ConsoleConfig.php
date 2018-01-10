<?php

namespace PhpUnitGen\Configuration;

use PhpUnitGen\Exception\InvalidConfigException;

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

        // Check boolean parameters
        if (! isset($config['overwrite']) || ! is_bool($config['overwrite'])) {
            throw new InvalidConfigException('"overwrite" parameter must be set as a boolean.');
        }

        if (! isset($config['auto']) || ! is_bool($config['auto'])) {
            throw new InvalidConfigException('"auto" parameter must be set as a boolean.');
        }
        if (! isset($config['ignore']) || ! is_bool($config['ignore'])) {
            throw new InvalidConfigException('"ignore" parameter must be set as a boolean.');
        }

        // Check string parameters
        if (! isset($config['include']) || ! is_string($config['include'])) {
            throw new InvalidConfigException('"include" parameter must be set as a string.');
        }
        if (! isset($config['exclude']) || ! is_string($config['exclude'])) {
            throw new InvalidConfigException('"exclude" parameter must be set as a string.');
        }

        // Check that dirs exists
        if (! isset($config['dirs']) || ! is_array($config['dirs']) || count($config['dirs']) < 1) {
            throw new InvalidConfigException('"dirs" parameter is not an array or does not contains elements.');
        }
        // Validate dirs
        foreach ($config['dirs'] as $srcDir => $testsDir) {
            if (! is_string($srcDir) || ! is_string($testsDir)) {
                throw new InvalidConfigException('Some directories in "dirs" parameter are not strings.');
            }
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
}
