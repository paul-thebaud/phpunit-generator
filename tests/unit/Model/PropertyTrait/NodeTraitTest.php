<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\ClassModel;
use PhpUnitGen\Model\PhpFileModel;

/**
 * Class NodeTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers \PhpUnitGen\Model\PropertyTrait\NodeTrait
 */
class NodeTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\NodeTrait::setParentNode()
     * @covers \PhpUnitGen\Model\PropertyTrait\NodeTrait::getParentNode()
     */
    public function testMethods(): void
    {
        $phpFile = new PhpFileModel();

        $class = new ClassModel();
        $class->setParentNode($phpFile);
        $this->assertSame($phpFile, $class->getParentNode());
    }
}
