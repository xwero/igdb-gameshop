<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTOCollection;

it('constructs with array of GameCoversRequestDTO objects', function() {
    $dto1 = new GameCoversRequestDTO(0, [1, 2]);
    $dto2 = new GameCoversRequestDTO(1, [3, 4]);
    
    $collection = new GameCoversRequestDTOCollection($dto1, $dto2);
    
    expect($collection)->toBeInstanceOf(GameCoversRequestDTOCollection::class);
    expect($collection->toArray())->toBe([$dto1, $dto2]);
});

it('is empty when constructed with no arguments', function() {
    $collection = new GameCoversRequestDTOCollection();
    expect($collection->isEmpty())->toBeTrue();
});

it('is not empty when constructed with requests', function() {
    $dto = new GameCoversRequestDTO(0, [1, 2]);
    $collection = new GameCoversRequestDTOCollection($dto);
    expect($collection->isEmpty())->toBeFalse();
});

it('can be iterated over', function() {
    $dto1 = new GameCoversRequestDTO(0, [1, 2]);
    $dto2 = new GameCoversRequestDTO(1, [3, 4]);
    $collection = new GameCoversRequestDTOCollection($dto1, $dto2);
    
    $items = [];
    foreach ($collection->toArray() as $item) {
        $items[] = $item;
    }
    
    expect(count($items))->toBe(2);
    expect($items[0])->toBe($dto1);
    expect($items[1])->toBe($dto2);
});