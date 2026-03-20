<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\Data;

use Xwero\IgdbGameshop\PIM\Tests\Unit\Data\Assets\TestProducts;


beforeEach(function () {
   $_ENV['DATABASE_DSN'] = 'sqlite::memory:';

    $this->products = new TestProducts();
});

it('inserts a product', function () {
    $row = [
      [
          'id' => 1,
          'name' => 'Game 1',
      ]
    ];

    $result = $this->products->multiInsert($row);

    expect($result)->toHaveCount(1);
    expect($result[0]['igdb_id'])->toBe(1);
});

it('inserts multiple products', function () {
    $rows = [
        [
            'id' => 1,
            'name' => 'Game 1',
        ],
        [
            'id' => 2,
            'name' => 'Game 2',
        ]
    ];

    $result = $this->products->multiInsert($rows);

    expect($result)->toHaveCount(2);
    expect($result[0]['igdb_id'])->toBe(1);
    expect($result[1]['igdb_id'])->toBe(2);
});