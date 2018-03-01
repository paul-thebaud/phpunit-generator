<?php

namespace UnitTests\PhpUnitGen\Executor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Executor\Executor;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\ParserInterface\PhpParserInterface;
use PhpUnitGen\Renderer\RendererInterface\PhpFileRendererInterface;

/**
 * Class ExecutorTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Executor\Executor
 */
class ExecutorTest extends TestCase
{
    /**
     * @var Executor $executor
     */
    private $executor;

    /**
     * @var ConfigInterface|MockObject $config
     */
    private $config;

    /**
     * @var PhpParserInterface|MockObject $phpFileParser
     */
    private $phpFileParser;

    /**
     * @var PhpFileRendererInterface|MockObject $phpFileRenderer
     */
    private $phpFileRenderer;

    /**
     * @var PhpFileModelInterface|MockObject $phpFileModel
     */
    private $phpFileModel;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->config          = $this->createMock(ConfigInterface::class);
        $this->phpFileParser   = $this->createMock(PhpParserInterface::class);
        $this->phpFileRenderer = $this->createMock(PhpFileRendererInterface::class);

        $this->executor = new Executor($this->config, $this->phpFileParser, $this->phpFileRenderer);

        $this->phpFileModel = $this->createMock(PhpFileModelInterface::class);

        $this->phpFileParser->expects($this->once())->method('invoke')
            ->with('<?php some php code')->willReturn($this->phpFileModel);
    }

    /**
     * @covers \PhpUnitGen\Executor\Executor::invoke()
     */
    public function testNoTestableFunctionsAndInterfaces(): void
    {
        $this->phpFileModel->expects($this->once())->method('setName')
            ->with('MyClassTest');
        $this->phpFileModel->expects($this->once())->method('getTestableFunctionsCount')
            ->with()->willReturn(0);
        $this->config->expects($this->once())->method('hasInterfaceParsing')
            ->with()->willReturn(true);
        $this->phpFileModel->expects($this->once())->method('getInterfacesFunctionsCount')
            ->with()->willReturn(5);

        $this->phpFileRenderer->expects($this->once())->method('invoke')
            ->with($this->phpFileModel)->willReturn('<?php generated tests skeleton');

        $this->assertSame(
            '<?php generated tests skeleton',
            $this->executor->invoke('<?php some php code', 'MyClassTest')
        );
    }

    /**
     * @covers \PhpUnitGen\Executor\Executor::invoke()
     */
    public function testNoTestableFunctionsAndNoInterfaces(): void
    {
        $this->phpFileModel->expects($this->once())->method('setName')
            ->with('MyClassTest');
        $this->phpFileModel->expects($this->once())->method('getTestableFunctionsCount')
            ->with()->willReturn(0);
        $this->config->expects($this->once())->method('hasInterfaceParsing')
            ->with()->willReturn(true);
        $this->phpFileModel->expects($this->once())->method('getInterfacesFunctionsCount')
            ->with()->willReturn(0);

        $this->phpFileRenderer->expects($this->never())->method('invoke');

        $this->assertSame(
            null,
            $this->executor->invoke('<?php some php code', 'MyClassTest')
        );
    }

    /**
     * @covers \PhpUnitGen\Executor\Executor::invoke()
     */
    public function testTestableFunctionsWithCustomName(): void
    {
        $this->phpFileModel->expects($this->once())->method('setName')
            ->with('MyClassTest');
        $this->phpFileModel->expects($this->once())->method('getTestableFunctionsCount')
            ->with()->willReturn(5);
        $this->config->expects($this->never())->method('hasInterfaceParsing');
        $this->phpFileModel->expects($this->never())->method('getInterfacesFunctionsCount');

        $this->phpFileRenderer->expects($this->once())->method('invoke')
            ->with($this->phpFileModel)->willReturn('<?php generated tests skeleton');

        $this->assertSame(
            '<?php generated tests skeleton',
            $this->executor->invoke('<?php some php code', 'MyClassTest')
        );
    }

    /**
     * @covers \PhpUnitGen\Executor\Executor::invoke()
     */
    public function testTestableFunctionsWithDefaultName(): void
    {
        $this->phpFileModel->expects($this->once())->method('setName')
            ->with('GeneratedTest');
        $this->phpFileModel->expects($this->once())->method('getTestableFunctionsCount')
            ->with()->willReturn(5);
        $this->config->expects($this->never())->method('hasInterfaceParsing');
        $this->phpFileModel->expects($this->never())->method('getInterfacesFunctionsCount');

        $this->phpFileRenderer->expects($this->once())->method('invoke')
            ->with($this->phpFileModel)->willReturn('<?php generated tests skeleton');

        $this->assertSame(
            '<?php generated tests skeleton',
            $this->executor->invoke('<?php some php code')
        );
    }
}
