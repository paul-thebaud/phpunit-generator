<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\FunctionModel;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;

/**
 * Class VisibilityTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\VisibilityTrait
 */
class VisibilityTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\VisibilityTrait::setVisibility()
     * @covers \PhpUnitGen\Model\PropertyTrait\VisibilityTrait::getVisibility()
     */
    public function testMethods(): void
    {
        $function = new FunctionModel();
        $this->assertTrue($function->isPublic());

        $function->setVisibility(VisibilityInterface::PUBLIC);
        $this->assertSame(VisibilityInterface::PUBLIC, $function->getVisibility());

        $this->assertTrue($function->isPublic());
        $function->setVisibility(VisibilityInterface::PROTECTED);
        $this->assertFalse($function->isPublic());
        $function->setVisibility(VisibilityInterface::PRIVATE);
        $this->assertFalse($function->isPublic());
    }
}
