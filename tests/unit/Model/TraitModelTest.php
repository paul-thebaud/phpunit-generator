<?php

namespace UnitTests\PhpUnitGen\Model;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\AttributeModel;
use PhpUnitGen\Model\TraitModel;

/**
 * Class TraitModelTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Model\TraitModel
 */
class TraitModelTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Model\TraitModel::addAttribute()
     * @covers \PhpUnitGen\Model\TraitModel::getAttributes()
     * @covers \PhpUnitGen\Model\TraitModel::hasAttribute()
     * @covers \PhpUnitGen\Model\TraitModel::getAttribute()
     */
    public function testMethods(): void
    {
        $trait = new TraitModel();

        $this->assertSame(0, $trait->getAttributes()->count());
        $this->assertFalse($trait->hasAttribute('attr1'));
        $this->assertNull($trait->getAttribute('attr1'));

        $attr1 = new AttributeModel();
        $attr1->setName('attr1');
        $attr2 = new AttributeModel();
        $attr2->setName('attr2');

        $trait->addAttribute($attr1);
        $trait->addAttribute($attr2);
        $this->assertSame(2, $trait->getAttributes()->count());
        $this->assertSame($attr1, $trait->getAttributes()->first());
        $this->assertSame($attr2, $trait->getAttributes()->get(1));
        $this->assertTrue($trait->hasAttribute('attr1'));
        $this->assertSame($attr1, $trait->getAttribute('attr1'));
    }
}
