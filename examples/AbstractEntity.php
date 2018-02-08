<?php

namespace Company\Entity;

/**
 * Class AbstractEntity.
 *
 * @PhpUnitGen\constructor(["1"])
 */
abstract class AbstractEntity
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @PhpUnitGen\getter
     */
    public function getId(): int
    {
        return $this->id;
    }
}
