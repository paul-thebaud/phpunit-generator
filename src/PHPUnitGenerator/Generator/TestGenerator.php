<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Generator;

use PHPUnitGenerator\CLI\Application;
use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Exception\DirNotFoundException;
use PHPUnitGenerator\Exception\ExceptionInterface\ExceptionInterface;
use PHPUnitGenerator\Exception\FileExistsException;
use PHPUnitGenerator\Exception\FileNotFoundException;
use PHPUnitGenerator\Exception\InvalidRegexException;
use PHPUnitGenerator\Exception\IsInterfaceException;
use PHPUnitGenerator\Generator\GeneratorInterface\TestGeneratorInterface;
use PHPUnitGenerator\Parser\CodeParser;
use PHPUnitGenerator\Parser\DocumentationParser;
use PHPUnitGenerator\Parser\ParserInterface\CodeParserInterface;
use PHPUnitGenerator\Parser\ParserInterface\DocumentationParserInterface;
use PHPUnitGenerator\Renderer\RendererInterface\TestRendererInterface;
use PHPUnitGenerator\Renderer\TwigTestRenderer;

/**
 * Class TestGenerator
 *
 *      An implementation of TestGenerator to generate tests with
 *      CodeParserInterface, DocumentationParserInterface and
 *      TestRendererInterface
 *
 * @package PHPUnitGenerator\Generator
 */
class TestGenerator implements TestGeneratorInterface
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * @var CodeParserInterface $codeParser The code parser to use
     */
    protected $codeParser;

    /**
     * @var DocumentationParserInterface $documentationParser The documentation
     *      parser to use
     */
    protected $documentationParser;

    /**
     * @var TestRendererInterface $testRenderer The tests renderer to use
     */
    protected $testRenderer;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;

        // By default, set dependencies to default interface implementation
        $this->codeParser = new CodeParser();
        $this->documentationParser = new DocumentationParser($config);
        $this->testRenderer = new TwigTestRenderer();
    }

    /**
     * {@inheritdoc}
     */
    public function generate(string $code): string
    {
        // Parse the PHP code
        $classModel = $this->codeParser->parse($code);

        if (true !== $this->config->getOption(ConfigInterface::OPTION_INTERFACE)
            && $classModel->isInterface()
        ) {
            throw new IsInterfaceException(IsInterfaceException::TEXT);
        }

        // Foreach methods, parse the documentation
        foreach ($classModel->getMethods() as $methodModel) {
            $methodModel->setAnnotations(
                $this->documentationParser->parse($methodModel)
            );
        }

        // Generate the test
        return $this->testRenderer->render($classModel);
    }

    /**
     * {@inheritdoc}
     */
    public function writeFile(string $inFile, string $outFile): int
    {
        if (! $this->fileExists($inFile)) {
            throw new FileNotFoundException(
                sprintf(FileNotFoundException::TEXT, $inFile)
            );
        }
        if ($this->fileExists($outFile) && true !== $this->config->getOption(ConfigInterface::OPTION_OVERWRITE)) {
            throw new FileExistsException(
                sprintf(FileExistsException::TEXT, $outFile)
            );
        }

        $testCode = $this->generate($this->read($inFile));

        if (! $this->fileExists(dirname($outFile), true)) {
            $this->mkDir(dirname($outFile));
        }

        $this->write($outFile, $testCode);

        Application::getPrinter()->info('"%s" tests generated', $inFile, $outFile);

        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function writeDir(string $inDir, string $outDir): int
    {
        // Check source dir
        if (! $this->fileExists($inDir, true)) {
            throw new DirNotFoundException(
                sprintf(
                    DirNotFoundException::TEXT,
                    $inDir
                )
            );
        }

        // Check regex validity
        if ($this->config->getOption(ConfigInterface::OPTION_INCLUDE) !== null
            && @preg_match($this->config->getOption(ConfigInterface::OPTION_INCLUDE), '') === false
        ) {
            throw new InvalidRegexException(InvalidRegexException::TEXT);
        }
        if ($this->config->getOption(ConfigInterface::OPTION_EXCLUDE) !== null
            && @preg_match($this->config->getOption(ConfigInterface::OPTION_EXCLUDE), '') === false
        ) {
            throw new InvalidRegexException(InvalidRegexException::TEXT);
        }

        // Check target dir
        if (! $this->fileExists($outDir, true)) {
            $this->mkDir($outDir);
        }

        // Foreach files
        $count = 0;
        foreach ($this->getFiles($inDir) as $inFile) {
            $outFile = str_replace($inDir, $outDir, $inFile);
            $outFile = preg_replace('/.php$/', 'Test.php', $outFile);

            try {
                $this->writeFile($inFile, $outFile);

                $count++;
            } catch (ExceptionInterface $exception) {
                if ($this->config->getOption(ConfigInterface::OPTION_IGNORE) !== true) {
                    throw $exception;
                } else {
                    Application::getPrinter()->warning(
                        "An error occurred during tests creation (for \"%s\"):\n\n\t%s",
                        $inFile,
                        $exception->getMessage()
                    );
                }
            }
        }

        return $count;
    }

    /**
     * Get the files of a directory and filter them
     *
     * @param string $dir
     *
     * @return array
     */
    protected function getFiles(string $dir): array
    {
        $fileList = [];

        $directory = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = new \IteratorIterator($iterator);

        foreach ($files as $file) {
            /**
             * @var \SplFileInfo $file
             */
            $file = $file->__toString();
            // If it is a file and if path match include regex and does not match exclude regex
            if ($this->fileExists($file)
                && ($this->config->getOption(ConfigInterface::OPTION_INCLUDE) === null
                    || preg_match($this->config->getOption(ConfigInterface::OPTION_INCLUDE), $file) > 0)
                && ($this->config->getOption(ConfigInterface::OPTION_EXCLUDE) === null
                    || preg_match($this->config->getOption(ConfigInterface::OPTION_EXCLUDE), $file) <= 0)
            ) {
                $fileList[] = $file;
            }
        }

        return $fileList;
    }

    /*
     **********************************************************************
     *
     * Setters to use custom parsers and renderer if needed
     *
     **********************************************************************
     */

    /**
     * @param CodeParserInterface $codeParser
     */
    public function setCodeParser(CodeParserInterface $codeParser)
    {
        $this->codeParser = $codeParser;
    }

    /**
     * @param DocumentationParser $documentationParser
     */
    public function setDocumentationParser(
        DocumentationParser $documentationParser
    ) {
        $this->documentationParser = $documentationParser;
    }

    /**
     * @param TestGeneratorInterface $testRenderer
     */
    public function setTestRenderer(TestGeneratorInterface $testRenderer)
    {
        $this->testRenderer = $testRenderer;
    }

    /*
     **********************************************************************
     *
     * Protected methods
     *
     **********************************************************************
     */

    /**
     * Check if the file exists and if it is a file or a dir
     *
     * @param string $file
     * @param bool   $isDir
     *
     * @return bool
     */
    protected function fileExists(string $file, bool $isDir = false): bool
    {
        return file_exists($file) && (($isDir && is_dir($file)) || (! $isDir && is_file($file)));
    }

    /**
     * Create a directory recursively
     *
     * @param string $dir
     */
    protected function mkDir(string $dir)
    {
        mkdir($dir, 0777, true);
    }

    /**
     * Write the $content in the $file
     *
     * @param string $file
     * @param string $content
     */
    protected function write(string $file, string $content)
    {
        file_put_contents($file, $content);
    }

    /**
     * Read the content of a $file
     *
     * @param string $file
     *
     * @return string
     */
    protected function read(string $file)
    {
        return file_get_contents($file);
    }
}
