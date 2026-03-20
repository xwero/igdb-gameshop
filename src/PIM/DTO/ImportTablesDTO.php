<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\DTO;

use Xwero\IgdbGameshop\PIM\Data\ProductCovers;
use Xwero\IgdbGameshop\PIM\Data\Products;

readonly class ImportTablesDTO
{
    public function __construct(public Products $products, public ProductCovers $productCovers)
    {}
}