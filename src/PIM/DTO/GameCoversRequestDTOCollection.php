<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\Shared\Collection;

class GameCoversRequestDTOCollection extends Collection
{
    public function __construct(GameCoversRequestDTO ...$coversRequestDTO)
    {
        $this->items = $coversRequestDTO;
    }
}