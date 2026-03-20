<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\Shared\Collection;

class GamesAndCoversFileDTOCollection extends Collection
{
    public function __construct(GamesAndCoversFileDTO ...$gamesAndCoversFileDTO)
    {
        $this->items = $gamesAndCoversFileDTO;
    }
}