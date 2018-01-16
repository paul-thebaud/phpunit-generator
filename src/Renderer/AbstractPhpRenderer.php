<?php

namespace PhpUnitGen\Renderer;

use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;

/**
 * Class AbstractRendererInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractPhpRenderer
{
    /**
     * @var ConfigInterface $config The configuration.
     */
    protected $config;

    /**
     * @var int $indent The indentation level.
     */
    protected $indent = 0;

    /**
     * @var string $content The content to show.
     */
    protected $content = '';

    /**
     * AbstractRendererInterface constructor.
     *
     * @param ConfigInterface $config The configuration to use.
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Begin a new render (clear content).
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function begin(): AbstractPhpRenderer
    {
        $this->content = '';
        return $this;
    }

    /**
     * Add a new line to content.
     *
     * @param string $line The new line (optional).
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function add(string $line = ''): AbstractPhpRenderer
    {
        if ($line === '') {
            $this->content .= "\n";
        } else {
            $this->content .= $this->indent($line) . "\n";
        }
        return $this;
    }

    /**
     * Add new lines to content.
     *
     * @param array $lines New lines.
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function multiple(array $lines): AbstractPhpRenderer
    {
        foreach ($lines as $line) {
            $this->add($line);
        }
        return $this;
    }

    /**
     * Add documentation block to content.
     *
     * @param array $documentation The documentation lines.
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function doc(array $documentation): AbstractPhpRenderer
    {
        $lines = ['/**'];
        foreach ($documentation as $line) {
            $lines[] = ' * ' . $line;
        }
        $lines[] = ' */';

        $this->multiple($lines);
        return $this;
    }

    /**
     * Add string at end of content.
     *
     * @param string $string The string to concat.
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function concat(string $string): AbstractPhpRenderer
    {
        $this->content .= $string;
        return $this;
    }

    /**
     * Remove n character at the content string.
     *
     * @param int $number The number of char to remove.
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function remove(int $number = 1): AbstractPhpRenderer
    {
        $this->content = substr($this->content, 0, (-$number));
        return $this;
    }

    /**
     * Increase the indent level.
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function increaseIndent(): AbstractPhpRenderer
    {
        $this->indent++;
        return $this;
    }

    /**
     * Decrease the indent level.
     *
     * @return AbstractPhpRenderer $this.
     */
    protected function decreaseIndent(): AbstractPhpRenderer
    {
        $this->indent++;
        return $this;
    }

    /**
     * Get the content.
     *
     * @return string The rendered content.
     */
    protected function get(): string
    {
        return $this->content;
    }

    /**
     * Add indent to a line line.
     *
     * @param string $line The line to indent.
     *
     * @return string The indented line.
     */
    private function indent(string $line): string
    {
        $indent = $this->indent;
        while ($indent > 0) {
            $line = '    ' . $line;
            $indent--;
        }
        return $line;
    }
}
