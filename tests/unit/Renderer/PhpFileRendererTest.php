<?php

namespace UnitTests\PhpUnitGen\Renderer;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Renderer\Helper\ParametersHelper;
use PhpUnitGen\Renderer\Helper\ValueHelper;
use PhpUnitGen\Renderer\PhpFileRenderer;
use Slim\Views\PhpRenderer;

/**
 * Class PhpFileRendererTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Renderer\PhpFileRenderer
 */
class PhpFileRendererTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Renderer\PhpFileRenderer::invoke()
     */
    public function testRender(): void
    {
        $config      = $this->createMock(ConfigInterface::class);
        $phpRenderer = $this->createMock(PhpRenderer::class);
        $phpFile     = $this->createMock(PhpFileModel::class);

        $phpRenderer->expects($this->exactly(3))->method('addAttribute')
            ->withConsecutive(
                ['config', $config],
                ['parametersHelper', $this->isInstanceOf(ParametersHelper::class)],
                ['valueHelper', $this->isInstanceOf(ValueHelper::class)]
            );
        $phpRenderer->expects($this->once())->method('fetch')
            ->with('class.php', ['phpFile' => $phpFile])
            ->willReturn('Renderered tests skeleton');

        $phpFileRenderer = new PhpFileRenderer($config, $phpRenderer);

        $this->assertSame('Renderered tests skeleton', $phpFileRenderer->invoke($phpFile));
    }
}
