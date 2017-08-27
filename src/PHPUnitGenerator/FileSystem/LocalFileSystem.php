<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\FileSystem;

use PHPUnitGenerator\Exception\DirNotFoundException;
use PHPUnitGenerator\Exception\FileNotFoundException;
use PHPUnitGenerator\FileSystem\FileSystemInterface\FileSystemInterface;

/**
 * Class LocalFileSystem
 *
 *      Allow to use the local storage to manage files and directories
 *
 * @package PHPUnitGenerator\FileSystem
 */
class LocalFileSystem implements FileSystemInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFiles(string $dir): array
    {
        $fileList = [];

        foreach ($this->getFilesIterator($dir) as $file) {
            /**
             * @var \SplFileInfo $file
             */
            if ($this->fileExists($file->__toString())) {
                $fileList[] = $file->__toString();
            }
        }

        return $fileList;
    }

    /**
     * {@inheritdoc}
     */
    public function filterFiles(array $files, string $includeRegex = null, string $excludeRegex = null): array
    {
        foreach ($files as $key => $file) {
            if (($includeRegex !== null && preg_match($includeRegex, $file) <= 0)
                || ($excludeRegex !== null && preg_match($excludeRegex, $file) > 0)
            ) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function mkDir(string $dir)
    {
        if (! $this->dirExists($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function pathExists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function fileExists(string $file): bool
    {
        return $this->pathExists($file) && is_file($file);
    }

    /**
     * {@inheritdoc}
     */
    public function dirExists(string $file): bool
    {
        return $this->pathExists($file) && is_dir($file);
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $file, string $content)
    {
        file_put_contents($file, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $file): string
    {
        if (! $this->fileExists($file)) {
            throw new FileNotFoundException(sprintf(FileNotFoundException::TEXT, $file));
        }
        return file_get_contents($file);
    }

    /**
     * Get the file iterator for a directory
     *
     * @param string $dir
     *
     * @return \IteratorIterator
     *
     * @throws DirNotFoundException If the directory does not exists
     */
    protected function getFilesIterator(string $dir)
    {
        if (! $this->dirExists($dir)) {
            throw new DirNotFoundException(sprintf(DirNotFoundException::TEXT, $dir));
        }

        $directory = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($directory);
        return new \IteratorIterator($iterator);
    }
}
