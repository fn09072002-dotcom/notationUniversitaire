<?php

declare(strict_types=1);

namespace App\Container;

use Closure;
use RuntimeException;

final class Container
{
    /** @var array<string, Closure> */
    private array $fabriques = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function set(string $id, Closure $fabrique): void
    {
        $this->fabriques[$id] = $fabrique;
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->fabriques[$id])) {
            throw new RuntimeException("Aucune définition enregistrée pour '$id'.");
        }

        $instance = ($this->fabriques[$id])($this);
        $this->instances[$id] = $instance;

        return $instance;
    }
}
