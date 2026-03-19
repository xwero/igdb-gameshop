<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

readonly class GameCoversResponseDTO
{
    public int $offset;
    public array $covers;

    public function __construct(int $offset, array $covers) {
        if(count($covers) == 0) {
            throw new \InvalidArgumentException('Covers array must not be empty.');
        }

        $this->offset = $offset;
        $this->covers = $covers;
    }
}