<?php
$namespace = $phpFile->getNamespaceString();
$namespace = sprintf('namespace Test%s;', ($namespace === null? '' : '\\' . $namespace));
?>
<?= $namespace ?>


use PHPUnit\Framework\TestCase;
<?php
foreach ($phpFile->getConcreteUses() as $use => $alias) {
    $nameArray = explode('\\', $use);
    $lastPart  = end($nameArray);
    if ($lastPart === $alias) {
        echo sprintf('use %s;', $use);
    } else {
        echo sprintf('use %s as %s;', $use, $alias);
    }
    echo "\n";
}
?>

/**
 * Class <?= $phpFile->getName() ?>.
<?php foreach ($config->getPhpDoc() as $annotation => $content) {
    echo sprintf(' * @%s %s', $annotation, $content);
    echo "\n";
} ?>
 *
<?php foreach ($phpFile->getClasses() as $class) {
    echo sprintf(' * @covers \\%s', $phpFile->getFullNameFor($class->getName()));
    echo "\n";
} ?>
<?php foreach ($phpFile->getTraits() as $trait) {
    echo sprintf(' * @covers \\%s', $phpFile->getFullNameFor($trait->getName()));
    echo "\n";
} ?>
<?php foreach ($phpFile->getInterfaces() as $interface) {
    echo sprintf(' * @covers \\%s', $phpFile->getFullNameFor($interface->getName()));
    echo "\n";
} ?>
 */
class <?= $phpFile->getName() ?> extends TestCase