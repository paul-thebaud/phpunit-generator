<?php

namespace UnitTests\PhpUnitGen\Model\PropertyTrait;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\PhpFileModel;

/**
 * Class NamespaceTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\PropertyTrait\NamespaceTrait
 */
class NamespaceTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\PropertyTrait\NamespaceTrait::setNamespace()
     * @covers \PhpUnitGen\Model\PropertyTrait\NamespaceTrait::getNamespace()
     * @covers \PhpUnitGen\Model\PropertyTrait\NamespaceTrait::getNamespaceString()
     * @covers \PhpUnitGen\Model\PropertyTrait\NamespaceTrait::getNamespaceLast()
     */
    public function testMethods(): void
    {
        $phpFile = new PhpFileModel();

        $this->assertSame([], $phpFile->getNamespace());
        $this->assertNull($phpFile->getNamespaceString());
        $this->assertNull($phpFile->getNamespaceLast());

        $phpFile->setNamespace(['My', 'Current', 'Namespace']);

        $this->assertSame(['My', 'Current', 'Namespace'], $phpFile->getNamespace());
        $this->assertSame('My\Current\Namespace', $phpFile->getNamespaceString());
        $this->assertSame('Namespace', $phpFile->getNamespaceLast());
    }
}
