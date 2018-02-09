        $this-><?= lcfirst($classLike->getName()) ?> = new <?= $constructorAnnotation->getClass() ?>(<?=
$this->getAttribute('parametersHelper')->invoke($constructorAnnotation->getParameters())
?>);