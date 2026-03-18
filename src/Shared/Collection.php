<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared;

abstract class Collection
{
    protected array $items = [];

    public function isEmpty(): bool
    {
        return count($this->items) == 0;
    }

    public function toArray(): array
    {
        return $this->items;
    }
}