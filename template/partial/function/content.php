<?php
foreach ($function->getMockAnnotations() as $mockAnnotation) {
    echo $this->fetch('annotation/local-mock.php', ['property' => $mockAnnotation->getProperty(), 'class' => $mockAnnotation->getClass()]);
}
if (count($function->getMockAnnotations()) > 0) {
    echo "\n";
}

if (($getter = $function->getGetterAnnotation()) !== null) { ?>
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete('This is a getter annotation');
<?php } else if (($getter = $function->getSetterAnnotation()) !== null) { ?>
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete('This is a setter annotation');
<?php
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