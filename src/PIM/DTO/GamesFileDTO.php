<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

class GamesFileDTO
{
    public int $offset;
    public array $content;
    public function __construct(int $offset, array $content)
    {
        if(count($content) == 0){
            throw new \InvalidArgumentException("The content array must have at least one item");
        }

        $this->offset = $offset;
        $this->content = $content;
    }
}