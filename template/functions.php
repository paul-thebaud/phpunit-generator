<?php foreach ($functions as $function): ?>

    /**
<?php if ($function->isGlobal()): ?>
     * Covers the global function "<?= $function->getName() ?>".
<?php else: ?>
     * @covers \<?= $function->getParentNode()->getParentNode()->getFullNameFor($function->getParentNode()->getName()) ?>::<?= $function->getName() ?>

<?php endif; ?>
     */
    public function test<?= ucfirst($function->getName()) ?>(): void
    {
<?= $this->fetch('partial/function/content.php', ['function' => $function]) ?>
    }
<?php endforeach; ?>