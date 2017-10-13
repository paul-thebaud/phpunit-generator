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
use PHPUnitGenerator\Exception\ExceptionInterface\ExceptionInterface;
use PHPUnitGenerator\Exception\FileExistsException;
use PHPUnitGenerator\Exception\InvalidRegexException;
use PHPUnitGenerator\Exception\IsInterfaceException;
use PHPUnitGenerator\FileSystem\FileSystemInterface\FileSystemInterface;
use PHPUnitGenerator\FileSystem\LocalFileSystem;
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
     * @var FileSystemInterface $fileSystem The file manager
     */
    protected $fileSystem;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;

        // By default, set dependencies to default interface implementation
        $this->setCodeParser(new CodeParser($config));
        $this->setDocumentationParser(new DocumentationParser($config));
        $this->setTestRenderer(new TwigTestRenderer());
        $this->setFileSystem(new LocalFileSystem());
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
        if ($this->fileSystem->fileExists($outFile)
            && true !== $this->config->getOption(ConfigInterface::OPTION_OVERWRITE)
        ) {
            throw new FileExistsException(sprintf(FileExistsException::TEXT, $outFile));
        }

        $testCode = $this->generate($this->fileSystem->read($inFile));

        $this->fileSystem->mkDir(dirname($outFile));

        $this->fileSystem->write($outFile, $testCode);

        Application::getPrinter()->info('"%s" tests generated', $inFile);

        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function writeDir(string $inDir, string $outDir): int
    {
        // Check regex validity
        $this->checkRegexValidity(ConfigInterface::OPTION_INCLUDE);
        $this->checkRegexValidity(ConfigInterface::OPTION_EXCLUDE);

        // Fix $outDir name
        if (substr($outDir, -1) !== '\\' && substr($outDir, -1) !== '/') {
            $outDir .= '/';
        }

        // Check target dir
        $this->fileSystem->mkDir($outDir);

        $files = $this->fileSystem->filterFiles(
            $this->fileSystem->getFiles($inDir),
            $this->config->getOption(ConfigInterface::OPTION_INCLUDE),
            $this->config->getOption(ConfigInterface::OPTION_EXCLUDE)
        );

        // Foreach files
        $count = 0;
        foreach ($files as $inFile) {
            $outFile = str_replace($inDir, $outDir, $inFile);
            $outFile = preg_replace('/(.php|.phtml)$/', '', $outFile) . 'Test.php';

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
     * @param DocumentationParserInterface $documentationParser
     */
    public function setDocumentationParser(
        DocumentationParserInterface $documentationParser
    ) {
        $this->documentationParser = $documentationParser;
    }

    /**
     * @param TestRendererInterface $testRenderer
     */
    public function setTestRenderer(TestRendererInterface $testRenderer)
    {
        $this->testRenderer = $testRenderer;
    }

    /**
     * @param FileSystemInterface $filesystem
     */
    public function setFileSystem(FileSystemInterface $filesystem)
    {
        $this->fileSystem = $filesystem;
    }

    /*
     **********************************************************************
     *
     * Protected methods
     *
     **********************************************************************
     */

    /**
     * Check the regex validity if set
     *
     * @param string $optionName
     *
     * @throws InvalidRegexException If a regex is invalid
     */
    protected function checkRegexValidity(string $optionName)
    {
        if ($this->config->getOption($optionName) !== null
            && @preg_match($this->config->getOption($optionName), '') === false
        ) {
            throw new InvalidRegexException(InvalidRegexException::TEXT);
        }
    }
}
