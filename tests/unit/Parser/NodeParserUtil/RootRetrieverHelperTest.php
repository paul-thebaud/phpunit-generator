<?php

namespace UnitTests\PhpUnitGen\Parser\NodeParserUtil;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\ParameterModel;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Parser\NodeParserUtil\RootRetrieverHelper;

/**
 * Class RootRetrieverHelperTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\NodeParserUtil\RootRetrieverHelper
 */
class RootRetrieverHelperTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Parser\NodeParserUtil\RootRetrieverHelper::getRoot()
     */
    public function testDifferentVisibility(): void
    {
        $parameter = new ParameterModel();

        $this->assertNull(RootRetrieverHelper::getRoot($parameter));

        $function = new FunctionModel();
        $parameter->setParentNode($function);

        $this->assertNull(RootRetrieverHelper::getRoot($parameter));

        $phpFile = new PhpFileModel();
        $function->setParentNode($phpFile);

        $this->assertSame($phpFile, RootRetrieverHelper::getRoot($parameter));
    }
}