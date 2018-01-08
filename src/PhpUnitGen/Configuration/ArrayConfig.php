<?php

namespace PhpUnitGen\Configuration;

use InvalidArgumentException;

/**
 * Class ArrayConfig.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ArrayConfig implements ConfigInterface
{
    /**
     * @var array $config The configuration as an array.
     */
    private $config = [];

    /**
     * Validate a configuration to know if it can be used to construct an instance.
     *
     * @param mixed $config The configuration to validate.
     *
     * @throws InvalidArgumentException If the $config is invalid.
     */
    public static function validate($config): void
    {
        // Check that dirs exists
        if (! isset($config['dirs']) || ! is_array($config['dirs']) || count($config['dirs']) < 1) {
            throw new InvalidArgumentException('No dirs to parse has been set in configuration.');
        }
        // Validate dirs
        foreach ($config['dirs'] as $srcDir => $testsDir) {
            if (! is_string($srcDir) || ! is_string($testsDir)) {
                throw new InvalidArgumentException('Some given dirs are not strings.');
            }
        }

        // Check boolean parameters
        if (! isset($config['overwrite']) || ! is_bool($config['overwrite'])) {
            throw new InvalidArgumentException('"overwrite" parameter must be set as a boolean.');
        }
        if (! isset($config['interface']) || ! is_bool($config['interface'])) {
            throw new InvalidArgumentException('"interface" parameter must be set as a boolean.');
        }
        if (! isset($config['auto']) || ! is_bool($config['auto'])) {
            throw new InvalidArgumentException('"auto" parameter must be set as a boolean.');
        }
    }

    /**
     * ArrayConfig constructor.
     *
     * @param mixed $config The configuration to use.
     */
    public function __construct($config)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function hasQuiet(): bool
    {
        // TODO: Implement hasQuiet() method.
    }

    /**
     * {@inheritdoc}
     */
    public function hasOverwrite(): bool
    {
        // TODO: Implement hasOverwrite() method.
    }

    /**
     * {@inheritdoc}
     */
    public function hasInterfaceParsing(): bool
    {
        // TODO: Implement hasInterfaceParsing() method.
    }

    /**
     * {@inheritdoc}
     */
    public function hasIgnore(): bool
    {
        // TODO: Implement hasIgnore() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getIncludeRegex(): string
    {
        // TODO: Implement getIncludeRegex() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getExcludeRegex(): string
    {
        // TODO: Implement getExcludeRegex() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectories(): array
    {
        // TODO: Implement getDirectories() method.
    }
}
