
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
<?php
echo "    {";

foreach ($phpFile->getMockAnnotations() as $mockAnnotation) {
    echo $this->fetch('annotation/global-mock-init.php', ['property' => $mockAnnotation->getProperty(), 'class' => $mockAnnotation->getClass()]);
}
if (count($phpFile->getMockAnnotations()) > 0) {
    echo "\n";
}

foreach($phpFile->getClassLikeCollection() as $classLike) {
    $parameters = [];
    $hasCustomConstructor = false;
    $constructorAnnotation = $classLike->getConstructAnnotation();

    // If the class has a construct annotation
    if ($constructorAnnotation !== null) {
        if ($constructorAnnotation->getClass() !== null) {
            $hasCustomConstructor = true;
            echo $this->fetch('annotation/constructor.php', ['classLike' => $classLike, 'constructorAnnotation' => $constructorAnnotation]);
        } else {
            $parameters = $constructorAnnotation->getParameters();
        }
    } else {
        // If the class is a class or trait, which has a __construct method and configuration auto parameter is set to true
        if ($this->getAttribute('config')->hasAuto()
            && $classLike instanceof \PhpUnitGen\Model\ModelInterface\TraitModelInterface
            && ($construct = $classLike->getFunction('__construct')) !== null
        ) {
            foreach ($construct->getParameters() as $parameter) {
                $parameters[] = $this->getAttribute('valueHelper')->invoke($parameter->getType(), $parameter->getCustomType());
            }
        }
    }

    if (! $hasCustomConstructor) {
        if ($classLike instanceof \PhpUnitGen\Model\ModelInterface\ClassModelInterface) {
            if ($classLike->isAbstract()) {
                echo $this->fetch('partial/fixture/abstract.php', ['class' => $classLike, 'parameters' => $parameters]);
            } else {
                echo $this->fetch('partial/fixture/class.php', ['class' => $classLike, 'parameters' => $parameters]);
            }
        } else if ($classLike instanceof \PhpUnitGen\Model\ModelInterface\TraitModelInterface) {
            echo $this->fetch('partial/fixture/trait.php', ['trait' => $classLike, 'parameters' => $parameters]);
        } else if ($classLike instanceof \PhpUnitGen\Model\ModelInterface\InterfaceModelInterface) {
            echo $this->fetch('partial/fixture/interface.php', ['interface' => $classLike, 'parameters' => $parameters]);
        }
    }
} ?>

    }
