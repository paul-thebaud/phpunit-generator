<?php

namespace UnitTests\PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameHelper;

/**
 * Class ClassLikeNameHelperTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameHelper
 */
class ClassLikeNameHelperTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameHelper::getName()
     */
    public function testDifferentVisibility(): void
    {
        $classLike = new Class_(null);
        $this->assertSame('UNKNOWN_NAME', ClassLikeNameHelper::getName($classLike));

        $classLike = new Class_('MyClass');
        $this->assertSame('MyClass', ClassLikeNameHelper::getName($classLike));
    }
}