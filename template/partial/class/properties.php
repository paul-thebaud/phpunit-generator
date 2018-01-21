<?php foreach ($phpFile->getClasses() as $class) {
    echo $this->fetch('partial/class/property.php', ['property' => $class->getName()]);
} ?>
<?php foreach ($phpFile->getTraits() as $trait) {
    echo $this->fetch('partial/class/property.php', ['property' => $trait->getName()]);
} ?>