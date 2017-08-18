<?php

namespace Test\PHPUnitGenerator\Model\AnnotationGetModel;

use PHPUnit\Framework\TestCase;
use PHPUnitGenerator\Model\AnnotationGetModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;

/**
 * Class AnnotationGetModelTest
 *
 * @covers \PHPUnitGenerator\Model\AnnotationGetModel
 */
class AnnotationGetModelTest extends TestCase
{
    /**
     * @var AnnotationGetModel $instance The class instance to test
     */
    protected $instance;

    /**
     * Build the instance of AnnotationGetModel
     */
    protected function setUp()
    {
        $this->instance = new AnnotationGetModel($this->createMock(MethodModelInterface::class));
    }

    /**
     * @covers \PHPUnitGenerator\Model\AnnotationGetModel::__construct()
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(MethodModelInterface::class, $this->instance->getParentMethod());
        $this->assertEquals(AnnotationModelInterface::TYPE_GET, $this->instance->getType());
    }
}
