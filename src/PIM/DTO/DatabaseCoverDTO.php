<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

readonly class DatabaseCoverDTO
{
    public int $productId;
    public string $url;
    public int $width;
    public int $height;

    public function __construct(int $productId, string $url, int $width, int $height)
    {
        if($productId <= 0){
            throw new \InvalidArgumentException('Product id cannot be less than zero or zero.');
        }

        if(strlen(trim($url)) === 0){
            throw new \InvalidArgumentException('URL cannot be empty.');
        }

        if($width <= 0){
            throw new \InvalidArgumentException('Width cannot be less than zero or zero.');
        }

        if($height <= 0){
            throw new \InvalidArgumentException('Height cannot be less than zero or zero.');
        }

        $this->productId = $productId;
        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
    }
}