<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\Shared\Collection;

class GameCoversResponseDTOCollection extends Collection
{
    public function __construct(GameCoversResponseDTO ...$coversResponseDTO)
    {
        $this->items = $coversResponseDTO;
    }
}