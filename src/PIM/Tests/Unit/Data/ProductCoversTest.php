<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\Data;

use Xwero\IgdbGameshop\PIM\DTO\DatabaseCoverDTO;
use Xwero\IgdbGameshop\PIM\DTO\DatabaseCoverDTOCollection;
use Xwero\IgdbGameshop\PIM\Tests\Unit\Data\Assets\TestProductCovers;

beforeEach(function () {
   $_ENV['DATABASE_DSN'] = 'sqlite::memory:';

   $this->productCovers = new TestProductCovers();
});

it('handles empty covers collection', function () {
    $result = $this->productCovers->multiInsert(new DatabaseCoverDTOCollection());

    expect($result)->toBe(0);
});

it('inserts a product cover', function () {
    $collection = new DatabaseCoverDTOCollection(
      new DatabaseCoverDTO(1,'http://example.com/1.jpg', 100, 100)
    );

    $result = $this->productCovers->multiInsert($collection);

    expect($result)->toBe(1);
});

it('inserts multiple products', function () {
    $collection = new DatabaseCoverDTOCollection(
        new DatabaseCoverDTO(1,'http://example.com/1.jpg', 100, 100),
        new DatabaseCoverDTO(2,'http://example.com/2.jpg', 100, 100)
    );

    $result = $this->productCovers->multiInsert($collection);

    expect($result)->toBe(2);
});