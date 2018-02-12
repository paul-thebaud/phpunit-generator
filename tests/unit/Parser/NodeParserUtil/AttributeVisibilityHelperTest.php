<?php

namespace UnitTests\PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node\Stmt\Property;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;
use PhpUnitGen\Parser\NodeParserUtil\AttributeVisibilityHelper;

/**
 * Class AttributeVisibilityHelperTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\NodeParserUtil\AttributeVisibilityHelper
 */
class AttributeVisibilityHelperTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Parser\NodeParserUtil\AttributeVisibilityHelper::getVisibility()
     */
    public function testDifferentVisibility(): void
    {
        $property = $this->createMock(Property::class);
        $property->expects($this->at(0))->method('isPrivate')
            ->with()->willReturn(true);
        $property->expects($this->at(1))->method('isPrivate')
            ->with()->willReturn(false);
        $property->expects($this->at(2))->method('isProtected')
            ->with()->willReturn(true);
        $property->expects($this->at(3))->method('isPrivate')
            ->with()->willReturn(false);
        $property->expects($this->at(4))->method('isProtected')
            ->with()->willReturn(false);

        $this->assertSame(VisibilityInterface::PRIVATE, AttributeVisibilityHelper::getVisibility($property));
        $this->assertSame(VisibilityInterface::PROTECTED, AttributeVisibilityHelper::getVisibility($property));
        $this->assertSame(VisibilityInterface::PUBLIC, AttributeVisibilityHelper::getVisibility($property));
    }
}