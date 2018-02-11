<?php

namespace UnitTests\PhpUnitGen\Model;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Annotation\ConstructAnnotation;
use PhpUnitGen\Model\InterfaceModel;

/**
 * Class InterfaceModelTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\InterfaceModel
 */
class InterfaceModelTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\InterfaceModel::getConstructAnnotation()
     */
    public function testMethods(): void
    {
        $interface = new InterfaceModel();

        $this->assertNull($interface->getConstructAnnotation());
        $constructAnnotation1 = new ConstructAnnotation();
        $constructAnnotation2 = new ConstructAnnotation();
        $interface->addAnnotation($constructAnnotation1);
        $interface->addAnnotation($constructAnnotation2);
        $this->assertSame($constructAnnotation1, $interface->getConstructAnnotation());
    }
}
