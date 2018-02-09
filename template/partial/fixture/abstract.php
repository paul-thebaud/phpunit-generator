<?php if (count($parameters) === 0) { ?>        /** @todo Maybe add some arguments to this constructor. */<?php } ?>

        $this-><?= lcfirst($class->getName()) ?> = $this->getMockBuilder(<?= $class->getName() ?>::class)<?php if (count($parameters) > 0) { ?>

            ->setConstructorArgs([<?= $this->getAttribute('parametersHelper')->invoke($parameters) ?>])<?php } else { ?>

            ->disableOriginalConstructor()<?php } ?>

            ->getMockForAbstractClass();