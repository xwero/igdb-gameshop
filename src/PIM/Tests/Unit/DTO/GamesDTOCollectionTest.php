<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;

it('constructs with array of GamesDTO objects', function() {
    $dto1 = new GamesDTO(0, '{"game": "Game 1"}');
    $dto2 = new GamesDTO(1, '{"game": "Game 2"}');
    
    $collection = new GamesDTOCollection($dto1, $dto2);
    
    expect($collection)->toBeInstanceOf(GamesDTOCollection::class);
    expect($collection->toArray())->toBe([$dto1, $dto2]);
});

it('is empty when constructed with no arguments', function() {
    $collection = new GamesDTOCollection();
    expect($collection->isEmpty())->toBeTrue();
});

it('is not empty when constructed with games', function() {
    $dto = new GamesDTO(0, '{"game": "Game 1"}');
    $collection = new GamesDTOCollection($dto);
    expect($collection->isEmpty())->toBeFalse();
});

it('can be iterated over', function() {
    $dto1 = new GamesDTO(0, '{"game": "Game 1"}');
    $dto2 = new GamesDTO(1, '{"game": "Game 2"}');
    $collection = new GamesDTOCollection($dto1, $dto2);
    
    $items = [];
    foreach ($collection->toArray() as $item) {
        $items[] = $item;
    }
    
    expect(count($items))->toBe(2);
    expect($items[0])->toBe($dto1);
    expect($items[1])->toBe($dto2);
});

it('returns correct array representation', function() {
    $dto1 = new GamesDTO(0, '{"game": "Game 1"}');
    $dto2 = new GamesDTO(1, '{"game": "Game 2"}');
    $collection = new GamesDTOCollection($dto1, $dto2);
    
    $array = $collection->toArray();
    expect($array)->toHaveCount(2);
    expect($array[0]->offset)->toBe(0);
    expect($array[1]->offset)->toBe(1);
});

it('handles single game', function() {
    $dto = new GamesDTO(0, '{"game": "Single Game"}');
    $collection = new GamesDTOCollection($dto);
    
    expect($collection->toArray())->toBe([$dto]);
    expect($collection->isEmpty())->toBeFalse();
});