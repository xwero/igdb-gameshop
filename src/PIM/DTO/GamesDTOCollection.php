<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\Shared\Collection;

class GamesDTOCollection extends Collection
{
    public function __construct(GamesDTO ...$gamesDTO)
    {
        $this->items = $gamesDTO;
    }
}