<?= $this->fetch('functions.php', ['functions' => $phpFile->getFunctions()]) ?>
<?php foreach ($phpFile->getClasses() as $class): ?>
<?= $this->fetch('functions.php', ['functions' => $class->getFunctions()]) ?>
<?php endforeach; foreach ($phpFile->getTraits() as $trait): ?>
<?= $this->fetch('functions.php', ['functions' => $trait->getFunctions()]) ?>
<?php endforeach; foreach ($phpFile->getInterfaces() as $interface): ?>
<?= $this->fetch('functions.php', ['functions' => $interface->getFunctions()]) ?>
<?php endforeach; ?>