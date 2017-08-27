<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\FileSystem\FileSystemInterface;

use PHPUnitGenerator\Exception\DirNotFoundException;
use PHPUnitGenerator\Exception\FileNotFoundException;

/**
 * Interface FileSystemInterface
 *
 *      Specifies which methods will contains a FileSystem
 *
 * @package PHPUnitGenerator\FileSystem\FileSystemInterface
 */
interface FileSystemInterface
{
    /**
     * Get the files of a directory
     *
     * @param string $dir
     *
     * @return array
     *
     * @throws DirNotFoundException If the directory does not exists
     */
    public function getFiles(string $dir): array;

    /**
     * Filter an array of files from regex
     *
     * @param array       $files
     * @param string|null $includeRegex
     * @param string|null $excludeRegex
     *
     * @return array
     */
    public function filterFiles(array $files, string $includeRegex = null, string $excludeRegex = null): array;

    /**
     * Create a directory (with recursive) if it does not exists
     *
     * @param string $dir
     */
    public function mkDir(string $dir);

    /**
     * Check if a path exists (even if its a file or a directory)
     *
     * @param string $path
     *
     * @return bool
     */
    public function pathExists(string $path): bool;

    /**
     * Check if a file exists
     *
     * @param string $file
     *
     * @return bool
     */
    public function fileExists(string $file): bool;

    /**
     * Check if a directory exists
     *
     * @param string $file
     *
     * @return bool
     */
    public function dirExists(string $file): bool;

    /**
     * Write content string in a file
     *
     * @param string $file
     * @param string $content
     */
    public function write(string $file, string $content);

    /**
     * Get the file content
     *
     * @param string $file
     *
     * @return string
     *
     * @throws FileNotFoundException If the file does not exists
     */
    public function read(string $file): string;
}
