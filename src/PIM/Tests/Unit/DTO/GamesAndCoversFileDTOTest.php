<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\DTO;

use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTO;

it('constructs with valid offset, games, and covers', function() {
    $dto = new GamesAndCoversFileDTO( [['id' => 1, 'name' => 'Game 1']], [['gameId' => 1, 'url' => 'http://example.com/cover.jpg']]);
    
    expect($dto)->toBeInstanceOf(GamesAndCoversFileDTO::class);
    expect($dto->games)->toBe([['id' => 1, 'name' => 'Game 1']]);
    expect($dto->covers)->toBe([['gameId' => 1, 'url' => 'http://example.com/cover.jpg']]);
});

it('requires non-empty games array', function() {
    $dto = new GamesAndCoversFileDTO( [['id' => 1], ['id' => 2]], [1]);
    expect($dto->games)->toHaveCount(2);
});

it('throws exception when games array is empty', function() {
    expect(fn() => new GamesAndCoversFileDTO( [], [1]))
        ->toThrow(\InvalidArgumentException::class, 'The games array must have at least one item');
});

it('throws exception when covers array is empty', function() {
    expect(fn() => new GamesAndCoversFileDTO( [1], []))
        ->toThrow(\InvalidArgumentException::class, 'The covers array must have at least one item');
});
