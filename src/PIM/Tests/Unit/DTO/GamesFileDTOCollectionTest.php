<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GamesFileDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesFileDTOCollection;

it('constructs with array of GamesFileDTO objects', function() {
    $dto1 = new GamesFileDTO(0, ['game1']);
    $dto2 = new GamesFileDTO(1, ['game2']);
    
    $collection = new GamesFileDTOCollection($dto1, $dto2);
    
    expect($collection)->toBeInstanceOf(GamesFileDTOCollection::class);
    expect($collection->toArray())->toBe([$dto1, $dto2]);
});

it('is empty when constructed with no arguments', function() {
    $collection = new GamesFileDTOCollection();
    expect($collection->isEmpty())->toBeTrue();
});

it('is not empty when constructed with games', function() {
    $dto = new GamesFileDTO(0, ['game1']);
    $collection = new GamesFileDTOCollection($dto);
    expect($collection->isEmpty())->toBeFalse();
});

it('can be iterated over', function() {
    $dto1 = new GamesFileDTO(0, ['game1']);
    $dto2 = new GamesFileDTO(1, ['game2']);
    $collection = new GamesFileDTOCollection($dto1, $dto2);
    
    $items = [];
    foreach ($collection->toArray() as $item) {
        $items[] = $item;
    }
    
    expect(count($items))->toBe(2);
    expect($items[0])->toBe($dto1);
    expect($items[1])->toBe($dto2);
});

it('returns correct array representation', function() {
    $dto1 = new GamesFileDTO(0, ['game1']);
    $dto2 = new GamesFileDTO(1, ['game2']);
    $collection = new GamesFileDTOCollection($dto1, $dto2);
    
    $array = $collection->toArray();
    expect($array)->toHaveCount(2);
    expect($array[0]->offset)->toBe(0);
    expect($array[1]->offset)->toBe(1);
});

it('handles single game file', function() {
    $dto = new GamesFileDTO(0, ['single_game']);
    $collection = new GamesFileDTOCollection($dto);
    
    expect($collection->toArray())->toBe([$dto]);
    expect($collection->isEmpty())->toBeFalse();
});

it('handles multiple games with different offsets', function() {
    $dto1 = new GamesFileDTO(0, ['game1']);
    $dto2 = new GamesFileDTO(100, ['game2']);
    $dto3 = new GamesFileDTO(200, ['game3']);
    $collection = new GamesFileDTOCollection($dto1, $dto2, $dto3);
    
    $array = $collection->toArray();
    expect($array)->toHaveCount(3);
    expect($array[0]->offset)->toBe(0);
    expect($array[1]->offset)->toBe(100);
    expect($array[2]->offset)->toBe(200);
});