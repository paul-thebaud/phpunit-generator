<?php

namespace Test\PHPUnitGenerator\FileSystem;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Exception\DirNotFoundException;
use PHPUnitGenerator\Exception\FileNotFoundException;
use PHPUnitGenerator\FileSystem\LocalFileSystem;

/**
 * Class LocalFileSystemTest
 *
 * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem
 */
class LocalFileSystemTest extends TestCase
{
    /**
     * @var LocalFileSystem $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of LocalFileSystem
     */
    protected function setUp()
    {
        $this->instance = new LocalFileSystem();
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::getFiles()
     */
    public function testGetFiles()
    {
        $fileInvalid1 = $this->createMock(\SplFileInfo::class);
        $fileInvalid1->expects($this->once())->method('__toString')->willReturn('file1');
        $fileInvalid2 = $this->createMock(\SplFileInfo::class);
        $fileInvalid2->expects($this->once())->method('__toString')->willReturn('file2');
        $fileValid = $this->createMock(\SplFileInfo::class);
        $fileValid->expects($this->exactly(2))->method('__toString')->willReturn(__FILE__);
        $fileIterator = [$fileInvalid1, $fileInvalid2, $fileValid];

        $mock = $this->getMockBuilder(LocalFileSystem::class)
            ->setMethods(['getFilesIterator'])->getMock();
        $mock->expects($this->once())->method('getFilesIterator')->with(__DIR__)
            ->willReturn($fileIterator);

        $this->assertEquals([__FILE__], $mock->getFiles(__DIR__));
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::filterFiles()
     */
    public function testFilterFiles()
    {
        $files = [
            0 => '/dir1/file1.php',
            1 => '/dir1/file2.php',
            2 => '/dir2/file1.php',
            3 => '/dir2/file2.png'
        ];

        $this->assertEquals($files, $this->instance->filterFiles($files));

        $this->assertEquals([2 => '/dir2/file1.php'], $this->instance->filterFiles($files, '#.*.php#', '#.*/dir1/.*#'));
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::pathExists()
     */
    public function testPathExists()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('pathExists');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->instance, __FILE__));
        $this->assertTrue($method->invoke($this->instance, __DIR__));
        $this->assertFalse($method->invoke($this->instance, __DIR__ . '/AFileOrDirThatDoesNotExists'));
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::fileExists()
     */
    public function testFileExists()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('fileExists');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->instance, __FILE__));
        $this->assertFalse($method->invoke($this->instance, __DIR__));
        $this->assertFalse($method->invoke($this->instance, __DIR__ . '/AFileThatDoesNotExists'));
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::dirExists()
     */
    public function testDirExists()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('dirExists');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->instance, __DIR__));
        $this->assertFalse($method->invoke($this->instance, __FILE__));
        $this->assertFalse($method->invoke($this->instance, __DIR__ . '/ADirThatDoesNotExists'));
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::mkDir()
     */
    public function testMkDir()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('mkDir');
        $method->setAccessible(true);

        $method->invoke($this->instance, __DIR__ . '/new/directories');
        $this->assertDirectoryExists(__DIR__ . '/new');
        $this->assertDirectoryExists(__DIR__ . '/new/directories');

        // Clear
        rmdir(__DIR__ . '/new/directories');
        rmdir(__DIR__ . '/new');
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::write()
     */
    public function testWrite()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('write');
        $method->setAccessible(true);

        $method->invoke($this->instance, __DIR__ . '/new_file', 'Some content');
        $this->assertFileExists(__DIR__ . '/new_file');
        $this->assertEquals('Some content', file_get_contents(__DIR__ . '/new_file'));

        $method->invoke($this->instance, __DIR__ . '/new_file', 'Some new content');
        $this->assertFileExists(__DIR__ . '/new_file');
        $this->assertEquals('Some new content', file_get_contents(__DIR__ . '/new_file'));

        unlink(__DIR__ . '/new_file');
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::read()
     */
    public function testRead()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('read');
        $method->setAccessible(true);

        file_put_contents(__DIR__ . '/new_file', 'Some content');

        $this->assertEquals('Some content', $method->invoke($this->instance, __DIR__ . '/new_file'));

        unlink(__DIR__ . '/new_file');

        $this->expectException(FileNotFoundException::class);
        $method->invoke($this->instance, __DIR__ . '/new_file');
    }

    /**
     * @covers \PHPUnitGenerator\FileSystem\LocalFileSystem::getFilesIterator()
     */
    public function testGetFilesIterator()
    {
        $method = (new \ReflectionClass(LocalFileSystem::class))->getMethod('getFilesIterator');
        $method->setAccessible(true);

        $this->assertInstanceOf(\IteratorIterator::class, $method->invoke($this->instance, __DIR__));

        $this->expectException(DirNotFoundException::class);
        $method->invoke($this->instance, __DIR__ . '/ADirThatDoesNotExists');
    }
}
