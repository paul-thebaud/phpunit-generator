<?php
/** @var \PhpUnitGen\Model\ModelInterface\PhpFileModelInterface $phpFile */
$phpFile = $this->getAttribute('phpFile');
/** @var \PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface $config */
$config = $this->getAttribute('config');
?>
<?= '<?php' ?>

<?php $namespace = $phpFile->getNamespaceString(); ?>

namespace Test<?php if ($namespace !== null) { echo '\\' . $namespace; } ?>;

use PHPUnit\Framework\TestCase;
<?php foreach ($phpFile->getConcreteUses() as $use => $name) { ?>
use <?= $use ?>;
<?php } ?>

/**
 * Class <?= $phpFile->getName() ?>.
 *
<?php foreach ($config->getPhpDoc() as $annotation => $content) { ?>
 * @<?= $annotation ?> <?= $content ?>

<?php } ?>
 *
<?php foreach ($phpFile->getInterfaces() as $interface) { ?>
 * @covers \<?= $phpFile->getFullNameFor($interface->getName()) ?>

<?php } ?>
<?php foreach ($phpFile->getTraits() as $trait) { ?>
 * @covers \<?= $phpFile->getFullNameFor($trait->getName()) ?>

<?php } ?>
<?php foreach ($phpFile->getClasses() as $class) { ?>
 * @covers \<?= $phpFile->getFullNameFor($class->getName()) ?>

<?php } ?>
 */
class <?= $phpFile->getName() ?> extends TestCase
{
<?= $this->getAttribute('content') ?>

}
