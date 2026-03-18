<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;
/**
 * @param string $json The data in the json are multiple games with their respective fields.
 */
readonly class GamesDTO
{
    public int $offset;

    public string $json;
    public function __construct(int $offset, string $json)
    {
        if(strlen(trim($json)) == 0 || json_validate($json) == false)
        {
            throw new \InvalidArgumentException("Invalid json");
        }

        $this->offset = $offset;
        $this->json = $json;
    }
}