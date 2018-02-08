<?php if ($assertAnnotation->getExpected() !== null) { ?>
        $this-><?= $assertAnnotation->getName() ?>(<?= $assertAnnotation->getExpected() ?>, $result);
<?php } else { ?>
        $this-><?= $assertAnnotation->getName() ?>($result);
<?php } ?>