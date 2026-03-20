<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\Shared\Collection;

class DatabaseCoverDTOCollection extends Collection
{
    public function __construct(DatabaseCoverDTO ...$databaseCoverDTOs)
    {
        $this->items = $databaseCoverDTOs;
    }
}