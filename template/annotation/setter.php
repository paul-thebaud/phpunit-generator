<?php
$parameter = $function->getParameters()->first();
if ($parameter !== false) {
    $expected = $this->getAttribute('valueHelper')->invoke($parameter->getType(), $parameter->getCustomType());
} else {
    $expected = $this->getAttribute('valueHelper')->invoke(\PhpUnitGen\Model\PropertyInterface\TypeInterface::MIXED);
}
?>        $expected = <?= $expected ?>;

        $property = (new \ReflectionClass($this-><?= lcfirst($function->getParentNode()->getName()) ?>))
            ->getProperty('<?= $setterAnnotation->getProperty() ?>');
        $property->setAccessible(true);
<?php if ($function->isStatic()) { ?>
        <?= $function->getParentNode()->getName() ?>::<?= $function->getName() ?>($expected);

        $this->assertSame($expected, $property->getValue(null));
<?php } else { ?>
        $this-><?= lcfirst($function->getParentNode()->getName()) ?>-><?= $function->getName() ?>($expected);

        $this->assertSame($expected, $property->getValue($this-><?= lcfirst($function->getParentNode()->getName()) ?>));
<?php } ?>