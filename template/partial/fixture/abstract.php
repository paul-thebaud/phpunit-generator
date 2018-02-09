
<?php if (count($parameters) === 0) {
    echo str_repeat(' ', 8) . '/** @todo Maybe add some arguments to this constructor */' . "\n";
} else if ($auto) {
    echo str_repeat(' ', 8) . '/** @todo Maybe check arguments of this constructor. */' . "\n";
} ?>
        $this-><?= lcfirst($class->getName()) ?> = $this->getMockBuilder(<?= $class->getName() ?>::class)<?php if (count($parameters) > 0) { ?>

            ->setConstructorArgs([<?= $this->getAttribute('parametersHelper')->invoke($parameters) ?>])<?php } else { ?>

            ->disableOriginalConstructor()<?php } ?>

            ->getMockForAbstractClass();