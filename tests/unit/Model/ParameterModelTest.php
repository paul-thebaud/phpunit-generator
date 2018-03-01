<?php

namespace UnitTests\PhpUnitGen\Model;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\ParameterModel;

/**
 * Class ParameterModelTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\ParameterModel
 */
class ParameterModelTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\ParameterModel::setIsVariadic()
     * @covers \PhpUnitGen\Model\ParameterModel::isVariadic()
     */
    public function testMethods(): void
    {
        $parameter = new ParameterModel();

        $parameter->setIsVariadic(true);
        $this->assertSame(true, $parameter->isVariadic());
    }
}
