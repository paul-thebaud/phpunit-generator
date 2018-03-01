<?php

namespace UnitTests\PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\PropertyInterface\VisibilityInterface;
use PhpUnitGen\Parser\NodeParserUtil\MethodVisibilityHelper;

/**
 * Class MethodVisibilityHelperTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\NodeParserUtil\MethodVisibilityHelper
 */
class MethodVisibilityHelperTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Parser\NodeParserUtil\MethodVisibilityHelper::getVisibility()
     */
    public function testDifferentVisibility(): void
    {
        $method = $this->createMock(ClassMethod::class);
        $method->expects($this->at(0))->method('isPrivate')
            ->with()->willReturn(true);
        $method->expects($this->at(1))->method('isPrivate')
            ->with()->willReturn(false);
        $method->expects($this->at(2))->method('isProtected')
            ->with()->willReturn(true);
        $method->expects($this->at(3))->method('isPrivate')
            ->with()->willReturn(false);
        $method->expects($this->at(4))->method('isProtected')
            ->with()->willReturn(false);

        $this->assertSame(VisibilityInterface::PRIVATE, MethodVisibilityHelper::getVisibility($method));
        $this->assertSame(VisibilityInterface::PROTECTED, MethodVisibilityHelper::getVisibility($method));
        $this->assertSame(VisibilityInterface::PUBLIC, MethodVisibilityHelper::getVisibility($method));
    }
}