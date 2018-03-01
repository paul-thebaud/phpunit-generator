        $expected = <?= $this->getAttribute('valueHelper')->invoke($function->getReturn()->getType(), $function->getReturn()->getCustomType()) ?>;

        $property = (new \ReflectionClass($this-><?= lcfirst($function->getParentNode()->getName()) ?>))
            ->getProperty('<?= $getterAnnotation->getProperty() ?>');
        $property->setAccessible(true);
<?php if ($function->isStatic()) { ?>
        $property->setValue(null, $expected);

        $this->assertSame($expected, <?= $function->getParentNode()->getName() ?>::<?= $function->getName() ?>());
<?php } else { ?>
        $property->setValue($this-><?= lcfirst($function->getParentNode()->getName()) ?>, $expected);

        $this->assertSame($expected, $this-><?= lcfirst($function->getParentNode()->getName()) ?>-><?= $function->getName() ?>());
<?php } ?>