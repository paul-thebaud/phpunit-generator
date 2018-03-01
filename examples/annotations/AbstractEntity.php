<?php

namespace Company\Entity;

/**
 * Class AbstractEntity.
 *
 * @PhpUnitGen\construct(["1"])
 */
abstract class AbstractEntity
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @PhpUnitGen\get
     */
    public function getId(): int
    {
        return $this->id;
    }
}
