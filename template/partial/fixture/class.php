
        /** @todo Maybe add some arguments to this constructor. */
        $this-><?= lcfirst($class->getName()) ?> = new <?= $class->getName() ?>(<?=
$this->getAttribute('parametersHelper')->invoke($parameters)
?>);