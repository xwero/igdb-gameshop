<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\DTO;

use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection;


it('is empty when constructed with no arguments', function() {
    $collection = new GamesAndCoversFileDTOCollection();
    expect($collection->isEmpty())->toBeTrue();
});

it('is not empty when constructed with items', function() {
    $dto = new GamesAndCoversFileDTO( [['id' => 1]], [1]);
    $collection = new GamesAndCoversFileDTOCollection($dto);
    expect($collection->isEmpty())->toBeFalse();
});

it('returns correct array representation', function() {
    $dto1 = new GamesAndCoversFileDTO([['id' => 1]], [1]);
    $dto2 = new GamesAndCoversFileDTO( [['id' => 2]], [1]);
    $collection = new GamesAndCoversFileDTOCollection($dto1, $dto2);
    
    $array = $collection->toArray();
    expect($array)->toHaveCount(2);
});

