
        /** @todo Maybe add some arguments to this constructor. */
        $this-><?= lcfirst($trait->getName()) ?> = $this->getMockBuilder(<?= $trait->getName() ?>::class)
            ->disableOriginalConstructor()->getMockForTrait();