<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection;

it('constructs with array of GameCoversResponseDTO objects', function() {
    $covers1 = [['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 100, 'height' => 200]];
    $covers2 = [['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 200, 'height' => 400]];
    
    $dto1 = new GameCoversResponseDTO(0, $covers1);
    $dto2 = new GameCoversResponseDTO(1, $covers2);
    
    $collection = new GameCoversResponseDTOCollection($dto1, $dto2);
    
    expect($collection)->toBeInstanceOf(GameCoversResponseDTOCollection::class);
    expect($collection->toArray())->toBe([$dto1, $dto2]);
});

it('is empty when constructed with no arguments', function() {
    $collection = new GameCoversResponseDTOCollection();
    expect($collection->isEmpty())->toBeTrue();
});

it('is not empty when constructed with responses', function() {
    $dto = new GameCoversResponseDTO(0, [1]);
    $collection = new GameCoversResponseDTOCollection($dto);
    expect($collection->isEmpty())->toBeFalse();
});

it('can be iterated over', function() {
    $dto1 = new GameCoversResponseDTO(0, [1]);
    $dto2 = new GameCoversResponseDTO(1, [1]);
    $collection = new GameCoversResponseDTOCollection($dto1, $dto2);
    
    $items = [];
    foreach ($collection->toArray() as $item) {
        $items[] = $item;
    }
    
    expect(count($items))->toBe(2);
    expect($items[0])->toBe($dto1);
    expect($items[1])->toBe($dto2);
});

it('returns correct array representation', function() {
    $dto1 = new GameCoversResponseDTO(0, [1]);
    $dto2 = new GameCoversResponseDTO(1, [1]);
    $collection = new GameCoversResponseDTOCollection($dto1, $dto2);
    
    $array = $collection->toArray();
    expect($array)->toHaveCount(2);
    expect($array[0]->offset)->toBe(0);
    expect($array[1]->offset)->toBe(1);
});

it('handles single response', function() {
    $dto = new GameCoversResponseDTO(0, [1]);
    $collection = new GameCoversResponseDTOCollection($dto);
    
    expect($collection->toArray())->toBe([$dto]);
    expect($collection->isEmpty())->toBeFalse();
});