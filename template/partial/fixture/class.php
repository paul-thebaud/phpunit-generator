<?php if (count($parameters) === 0) { ?>        /** @todo Maybe add some arguments to this constructor. */<?php } ?>

        $this-><?= lcfirst($class->getName()) ?> = new <?= $class->getName() ?>(<?=
$this->getAttribute('parametersHelper')->invoke($parameters)
?>);