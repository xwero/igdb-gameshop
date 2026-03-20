<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\Data\Assets;

use Xwero\IgdbGameshop\PIM\Data\ProductCovers;

class TestProductCovers extends ProductCovers {
    public function __construct()
    {
        parent::__construct();

        $sql = 'CREATE table pim_product_covers (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER,
                    url VARCHAR(255),
                    width INTEGER,
                    height INTEGER,
                    created_at DATETIME,
                    updated_at DATETIME
              );';

        $this->pdo->exec($sql);
    }
}