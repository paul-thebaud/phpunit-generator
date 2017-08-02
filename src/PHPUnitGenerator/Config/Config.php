<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Config;

use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;

/**
 * Class ConfigTrait
 *
 *      A class when options are needed (like DocumentationParser)
 *
 * @package PHPUnitGenerator\Config
 */
class Config implements ConfigInterface
{
    /**
     * @var array $options The options array
     */
    protected $options = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }
}
