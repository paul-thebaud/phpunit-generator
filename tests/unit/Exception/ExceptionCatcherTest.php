<?php

namespace UnitTests\PhpUnitGen\Exception;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConsoleConfigInterface;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Exception\ExceptionCatcher;
use PhpUnitGen\Exception\IgnorableException;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class ExceptionCatcherTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Exception\ExceptionCatcher
 */
class ExceptionCatcherTest extends TestCase
{
    /**
     * @var ConsoleConfigInterface|MockObject $config
     */
    private $config;

    /**
     * @var StyleInterface|MockObject $output
     */
    private $output;

    /**
     * @var ExceptionCatcher $exceptionCatcher
     */
    private $exceptionCatcher;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->config = $this->createMock(ConsoleConfigInterface::class);
        $this->output = $this->createMock(StyleInterface::class);

        $this->exceptionCatcher = new ExceptionCatcher($this->config, $this->output);
    }

    /**
     * @covers \PhpUnitGen\Exception\ExceptionCatcher::catch()
     */
    public function testHasIgnoreAndIsIgnorable(): void
    {
        $exception = new IgnorableException('Ignorable exception');

        $this->config->expects($this->once())->method('hasIgnore')
            ->with()->willReturn(true);

        $this->output->expects($this->once())->method('note')
            ->with('On file "file.php": Ignorable exception');

        $this->exceptionCatcher->catch($exception, 'file.php');
    }

    /**
     * @covers \PhpUnitGen\Exception\ExceptionCatcher::catch()
     */
    public function testHasIgnoreAndIsNotIgnorable(): void
    {
        $exception = new Exception('Not ignorable exception');

        $this->config->expects($this->once())->method('hasIgnore')
            ->with()->willReturn(true);

        $this->output->expects($this->never())->method('note');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not ignorable exception');

        $this->exceptionCatcher->catch($exception, 'file.php');
    }

    /**
     * @covers \PhpUnitGen\Exception\ExceptionCatcher::catch()
     */
    public function testHasNotIgnore(): void
    {
        $exception = new Exception('Not ignorable exception');

        $this->config->expects($this->once())->method('hasIgnore')
            ->with()->willReturn(false);

        $this->output->expects($this->never())->method('note');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not ignorable exception');

        $this->exceptionCatcher->catch($exception, 'file.php');
    }
}
