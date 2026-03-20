<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

class GamesAndCoversFileDTO
{
    public array $games;
    public array $covers;
    
    public function __construct(array $games, array $covers)
    {
        if (count($games) === 0) {
            throw new \InvalidArgumentException("The games array must have at least one item");
        }

        if (count($covers) === 0) {
            throw new \InvalidArgumentException("The covers array must have at least one item");
        }

        $this->games = $games;
        $this->covers = $covers;
    }
}