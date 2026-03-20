<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\Data\Assets;

use Xwero\IgdbGameshop\PIM\Data\Products;

class TestProducts extends Products {
    public function __construct()
    {
        parent::__construct();

        $sql = 'CREATE table pim_products (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    igdb_id INTEGER,
                    name VARCHAR(255),
                    created_at DATETIME,
                    updated_at DATETIME
              );';

        $this->pdo->exec($sql);
    }
}