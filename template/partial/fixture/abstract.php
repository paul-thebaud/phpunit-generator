
        /** @todo Maybe add some arguments to this constructor. */
        $this-><?= lcfirst($class->getName()) ?> = $this->getMockBuilder(<?= $class->getName() ?>::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();