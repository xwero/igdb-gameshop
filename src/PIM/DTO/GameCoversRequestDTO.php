<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

readonly class GameCoversRequestDTO
{
    public int $offset;
    public array $games;
    public function __construct(int $offset, array $games) {
        if(count($games) == 0) {
            throw new \InvalidArgumentException('There should be at least one game in the games array.');
        }

        $this->offset = $offset;
        $this->games = $games;
    }
}