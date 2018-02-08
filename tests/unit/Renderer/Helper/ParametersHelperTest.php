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
 * Class ParametersHelperTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Renderer\Helper\ParametersHelper
 */
class ParametersHelperTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Renderer\Helper\ParametersHelper::invoke()
     */
    public function testInvokeOnParameters(): void
    {
        $parametersHelper = new ParametersHelper();

        $this->assertSame('param1, param2, param3', $parametersHelper->invoke(['param1', 'param2', 'param3']));
        $this->assertSame('', $parametersHelper->invoke([]));
    }
}
