<?php

namespace App\Entity;

abstract class AbstractDocument
{
    protected ?int $id = null;
    protected string $dateDepot;

    protected function __construct(string $dateDepot, ?int $id = null)
    {
        $this->id = $id;
        $this->dateDepot = $dateDepot;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDateDepot(): string
    {
        return $this->dateDepot;
    }
}
