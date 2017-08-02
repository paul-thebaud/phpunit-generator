<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Generator\GeneratorInterface;

use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Exception\DirNotFoundException;
use PHPUnitGenerator\Exception\EmptyFileException;
use PHPUnitGenerator\Exception\FileNotFoundException;
use PHPUnitGenerator\Exception\InvalidCodeException;
use PHPUnitGenerator\Exception\IsInterfaceException;

/**
 * Interface TestGeneratorInterface
 *
 *      Specifies which methods will contains a TestGenerator
 *      A TestGenerator will allow to convert PHP code into tests class file content,
 *      write from a file to a file, or write from directory files to another directory files.
 *
 * @package PHPUnitGenerator\Generator\GeneratorInterface
 */
interface TestGeneratorInterface
{
    /**
     * TestGeneratorInterface constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config);

    /**
     * Generate tests class content for a specific PHP code
     *
     * @param string $code
     *
     * @return string
     *
     * @throws InvalidCodeException
     * @throws EmptyFileException
     * @throws IsInterfaceException
     */
    public function generate(string $code): string;

    /**
     * Write tests class content for a specific PHP file to another file
     *
     * @param string $inFile
     * @param string $outFile
     *
     * @throws FileNotFoundException
     * @throws InvalidCodeException
     * @throws EmptyFileException
     * @throws IsInterfaceException
     */
    public function writeFile(string $inFile, string $outFile);

    /**
     * Write tests class content for all PHP files in a dir to other files
     *
     * @param string $inDir
     * @param string $outDir
     *
     * @throws DirNotFoundException
     * @throws FileNotFoundException
     * @throws InvalidCodeException
     * @throws EmptyFileException
     * @throws IsInterfaceException
     */
    public function writeDir(string $inDir, string $outDir);
}
