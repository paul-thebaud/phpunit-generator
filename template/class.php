<?= "<?php\n" ?>

<?= $this->fetch('partial/class/header.php', ['phpFile' => $phpFile]) ?>

<?= '{' . $this->fetch('partial/class/properties.php', ['phpFile' => $phpFile]) ?>
<?= $this->fetch('partial/class/fixture.php', ['phpFile' => $phpFile]) ?>
<?= $this->fetch('partial/class/content.php', ['phpFile' => $phpFile]) . '}' ?>

