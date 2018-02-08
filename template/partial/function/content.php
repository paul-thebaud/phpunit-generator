<?php
foreach ($function->getMockAnnotations() as $mockAnnotation) {
    echo $this->fetch('annotation/local-mock.php', ['property' => $mockAnnotation->getProperty(), 'class' => $mockAnnotation->getClass()]);
}
if (count($function->getMockAnnotations()) > 0) {
    echo "\n";
}

if (($getter = $function->getGetterAnnotation()) !== null) {
    echo $this->fetch('annotation/getter.php', ['function' => $function, 'getterAnnotation' => $getter]);
} else if (($setter = $function->getSetterAnnotation()) !== null) {
    echo $this->fetch('annotation/setter.php', ['function' => $function, 'setterAnnotation' => $setter]);
} else if (count($function->getAssertAnnotations()) > 0) {
    echo $this->fetch('partial/function/call.php', ['function' => $function]);
    foreach ($function->getAssertAnnotations() as $assertAnnotation) {
?>
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete('This is an "<?= $assertAnnotation->getName() ?>" annotation');
<?php
    }
} else {
?>
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
<?php } ?>