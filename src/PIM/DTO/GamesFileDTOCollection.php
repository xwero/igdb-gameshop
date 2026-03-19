<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\Shared\Collection;

class GamesFileDTOCollection extends Collection
{
    public function __construct(GamesFileDTO ...$gamesFileDTO)
    {
        $this->items = $gamesFileDTO;
    }
}