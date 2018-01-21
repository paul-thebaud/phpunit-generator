
    /**
     * Set up instances for each unit tests.
     */
    protected function setUp()
<?php
echo "    {";
foreach ($phpFile->getClasses() as $class) {
    if ($class->isAbstract()) {
        echo $this->fetch('partial/fixture/abstract.php', ['class' => $class]);
    } else {
        echo $this->fetch('partial/fixture/class.php', ['class' => $class]);
    }
}
foreach ($phpFile->getTraits() as $trait) {
    echo $this->fetch('partial/fixture/trait.php', ['trait' => $trait]);
}
foreach ($phpFile->getInterfaces() as $interface) {
    echo $this->fetch('partial/fixture/interface.php', ['interface' => $interface]);
} ?>

    }
