<?php

namespace UnitTests\PhpUnitGen\Validator;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\Handler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\FileNotFoundException;
use PhpUnitGen\Validator\FileValidator;

/**
 * Class FileValidatorTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Validator\FileValidator
 */
class FileValidatorTest extends TestCase
{
    /**
     * @var ConsoleConfigInterface|MockObject $config
     */
    private $config;

    /**
     * @var FilesystemInterface|MockObject $fileSystem
     */
    private $fileSystem;

    /**
     * @var FileValidator $fileValidator
     */
    private $fileValidator;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->config     = $this->createMock(ConsoleConfigInterface::class);
        $this->fileSystem = $this->createMock(FilesystemInterface::class);

        $this->fileValidator = new FileValidator($this->config, $this->fileSystem);
    }

    /**
     * @covers \PhpUnitGen\Validator\FileValidator::validate()
     * @covers \PhpUnitGen\Validator\FileValidator::validatePath()
     */
    public function testValidatePathDoesNotExists(): void
    {
        $this->fileSystem->expects($this->once())->method('has')
            ->with('my_file.php')->willReturn(false);

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('The source file "my_file.php" does not exist.');

        $this->fileValidator->validate('my_file.php');
    }

    /**
     * @covers \PhpUnitGen\Validator\FileValidator::validate()
     * @covers \PhpUnitGen\Validator\FileValidator::validatePath()
     */
    public function testValidatePathIsDirectory(): void
    {
        $handler = $this->createMock(Handler::class);

        $this->fileSystem->expects($this->once())->method('has')
            ->with('my_dir')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('my_dir')->willReturn($handler);

        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('dir');

        $this->assertFalse($this->fileValidator->validate('my_dir'));
    }

    /**
     * @covers \PhpUnitGen\Validator\FileValidator::validate()
     * @covers \PhpUnitGen\Validator\FileValidator::validatePath()
     * @covers \PhpUnitGen\Validator\FileValidator::validateIncludeRegex()
     * @covers \PhpUnitGen\Validator\FileValidator::validateExcludeRegex()
     */
    public function testValidatePathNoRegex(): void
    {
        $handler = $this->createMock(Handler::class);

        $this->fileSystem->expects($this->once())->method('has')
            ->with('my_file.php')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('my_file.php')->willReturn($handler);

        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('file');

        $this->config->expects($this->once())->method('getIncludeRegex')
            ->with()->willReturn(null);
        $this->config->expects($this->once())->method('getExcludeRegex')
            ->with()->willReturn(null);

        $this->assertTrue($this->fileValidator->validate('my_file.php'));
    }

    /**
     * @covers \PhpUnitGen\Validator\FileValidator::validate()
     * @covers \PhpUnitGen\Validator\FileValidator::validatePath()
     * @covers \PhpUnitGen\Validator\FileValidator::validateIncludeRegex()
     * @covers \PhpUnitGen\Validator\FileValidator::validateExcludeRegex()
     */
    public function testValidatePathDoesNotMatchIncludeRegex(): void
    {
        $handler = $this->createMock(Handler::class);

        $this->fileSystem->expects($this->once())->method('has')
            ->with('my_file.phtml')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('my_file.phtml')->willReturn($handler);

        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('file');

        $this->config->expects($this->once())->method('getIncludeRegex')
            ->with()->willReturn('/^.*\.php$/');
        $this->config->expects($this->never())->method('getExcludeRegex');

        $this->assertFalse($this->fileValidator->validate('my_file.phtml'));
    }

    /**
     * @covers \PhpUnitGen\Validator\FileValidator::validate()
     * @covers \PhpUnitGen\Validator\FileValidator::validatePath()
     * @covers \PhpUnitGen\Validator\FileValidator::validateIncludeRegex()
     * @covers \PhpUnitGen\Validator\FileValidator::validateExcludeRegex()
     */
    public function testValidatePathMatchIncludeRegexAndExcludeRegex(): void
    {
        $handler = $this->createMock(Handler::class);

        $this->fileSystem->expects($this->once())->method('has')
            ->with('my_file.config.php')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('my_file.config.php')->willReturn($handler);

        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('file');

        $this->config->expects($this->once())->method('getIncludeRegex')
            ->with()->willReturn('/^.*\.php$/');
        $this->config->expects($this->once())->method('getExcludeRegex')
            ->with()->willReturn('/^.*\.config\.php$/');

        $this->assertFalse($this->fileValidator->validate('my_file.config.php'));
    }

    /**
     * @covers \PhpUnitGen\Validator\FileValidator::validate()
     * @covers \PhpUnitGen\Validator\FileValidator::validatePath()
     * @covers \PhpUnitGen\Validator\FileValidator::validateIncludeRegex()
     * @covers \PhpUnitGen\Validator\FileValidator::validateExcludeRegex()
     */
    public function testValidatePathMatchIncludeRegexDoesNotMatchExcludeRegex(): void
    {
        $handler = $this->createMock(Handler::class);

        $this->fileSystem->expects($this->once())->method('has')
            ->with('my_file.php')->willReturn(true);
        $this->fileSystem->expects($this->once())->method('get')
            ->with('my_file.php')->willReturn($handler);

        $handler->expects($this->once())->method('getType')
            ->with()->willReturn('file');

        $this->config->expects($this->once())->method('getIncludeRegex')
            ->with()->willReturn('/^.*\.php$/');
        $this->config->expects($this->once())->method('getExcludeRegex')
            ->with()->willReturn('/^.*\.config\.php$/');

        $this->assertTrue($this->fileValidator->validate('my_file.php'));
    }
}
